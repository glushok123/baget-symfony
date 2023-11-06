<?php

namespace App\Service;

use App\Dto\Appeal\AppealCategory\AppealCategoryDto;
use App\Dto\Appeal\AppealDto;
use App\Dto\Appeal\AppealMessage\AppealMessageDto;
use App\Dto\Appeal\Filter\FilterDto;
use App\Entity\Appeal\Appeal;
use App\Entity\Appeal\AppealMessage;
use App\Entity\Appeal\AppealStatus;
use App\Entity\User\User;
use App\Repository\AbstractBasicRepository;
use App\Repository\AppealCategoryRepository;
use App\Repository\AppealMessageRepository;
use App\Repository\AppealRepository;

use App\Repository\AppealStatusRepository;
use Exception;
use Doctrine\Common\Annotations\AnnotationReader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;
use DateTimeImmutable;

class AppealService
{
    private $serializer;

    public function __construct(
        private readonly AppealRepository              $repository,
        private readonly AppealStatusRepository        $statusRepository,
        private readonly AppealCategoryRepository      $categoryRepository,
        private readonly AppealMessageRepository       $messageRepository,
        private readonly TranslatorInterface           $translator,

    )
    {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);

        $this->serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);
    }

    public function create(AppealDto $dto, User $user): AppealDto
    {
        $appeal = new Appeal();
        $appeal->setUser($user);
        $appeal->setName($dto->name);
        $appeal->setStatus($this->statusRepository->findOneBy(['name' => AppealStatus::STATUS_NEW]));
        $appeal->setCategory($this->categoryRepository->findOneBy(['id' => $dto->category->id]));

        $message = new AppealMessage();
        $message->setMessage($dto->message);
        $message->setSender($user);
        $message->setCreatedAt(new DateTimeImmutable());
        $this->messageRepository->save($message);

        $appeal->addAppealMessage($message);
        $appeal->setCreatedAt(new DateTimeImmutable());

        $this->repository->save($appeal);

        return $this->serialize($message, AppealDto::class);
    }

    public function getCollection(PaginatorInterface $paginator, FilterDto $dto, User $user, array $appealDto = [], array $appealMessages = []): array
    {
        $appeals = $this->repository->findBy(['user' => $user]);

        if (empty($appeals)) {
            throw new Exception($this->translator->trans('appeal_could_not_be_found'));
        }

        $pagination = $paginator->paginate($appeals, $dto->page ?? 1, $dto->limit ?? 8);

        foreach ($pagination->getItems() as $appeal) {
            foreach ($appeal->getAppealMessages() as $message) {
                $appealMessages[] = $this->serialize($message, AppealMessageDto::class)->toArray(['appeal']);
            }

            $appealDto[] = $this->serialize($appeal, AppealDto::class, ['appealMessages'], [], ['appealMessages' => $appealMessages])->toArray(['appeal']);
        }

        return $appealDto;
    }

    public function serialize($entity, $dto, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = [])
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);
        $normalize = array_merge($this->serializer->normalize($entity, null, $condition), $addAttribute);

        return $this->serializer->denormalize(
            $normalize, $dto
        );
    }
}
