<?php

namespace App\Service;

use App\Dto\Auth\RegisterOrganizationDto;
use App\Dto\Organization\OrganizationDto;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Dto\User\UserDto;
use App\Entity\Organization\Organization;
use App\Entity\Organization\OrganizationMessenger;
use App\Entity\User\User;
use App\Repository\CountryRepository;
use App\Repository\MessengerRepository;
use App\Repository\OrganizationMessengerRepository;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationTypeRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationService
{
    const DADATA_FIND_BY_INN_URI = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party';

    private $serializer;

    public function __construct(
        private readonly string                             $dadataKey,
        private readonly OrganizationRepository             $repository,
        private readonly OrganizationTypeRepository         $organizationTypeRepository,
        private readonly MessengerRepository                $messengerRepository,
        private readonly HttpClientInterface                $client,
        private readonly UserRepository                     $userRepository,
        private readonly CountryRepository                  $countryRepository,
        private readonly OrganizationMessengerRepository    $organizationMessengerRepository,
        private readonly TranslatorInterface                $translator,
        private readonly JWTTokenManagerInterface           $tokenManager,
        private readonly TokenStorageInterface              $tokenStorageInterface
    ) {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);

        $this->serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);
    }

    public function register(RegisterOrganizationDto $dto, UserDto $userDto, array $dadata): OrganizationDto
    {
        if ($this->repository->findOneBy(['inn' => $dto->inn]) || $this->repository->findOneBy(['phone' => $dto->phone])) {
            throw new Exception($this->translator->trans('organization_already_exists'), 500);
        }

        $publicType = !empty($dto->publicType) ? $this->organizationTypeRepository->find($dto->publicType->id) : null;
        $country = $this->countryRepository->findOneBy(['id' => $dto->country->id]);

        if ($dto->country->id == $this->countryRepository->findOneBy(['name' => "Россия"])->getId()) {
            $name = empty($dadata) ? null : $dadata['data']['name']['full_with_opf'];
            $shortName = empty($dadata) ? null : $dadata['data']['name']['short_with_opf'];
            $legalAddress = empty($dadata) ? null : $dadata['data']['address']['value'];
        }else{
            $name = empty($dto->name) ? null : $dto->name;
            $shortName = empty($shortName) ? null : $shortName;
            $legalAddress = empty($dto->legalAddress) ? null : $dto->legalAddress;
        }

        $organization = new Organization();
        $organization->setCreatedAt(new DateTimeImmutable());
        $organization->setInn($dto->inn);
        $organization->setName($name);
        $organization->setShortName($shortName);
        $organization->setLegalAddress($legalAddress);
        $organization->setDescription($dto->description);
        $organization->setTypePublic($publicType);
        $organization->setPhone($dto->phone);
        $organization->setSite($dto->site);
        $organization->setOwner($this->userRepository->findOneBy(['email' => $userDto->email]));
        $organization->setCountry($country);

        foreach ($dto->messengers as $messengerDto) {
            if (empty($messengerDto['value'])) {
                continue;
            }

            $messengerType = $this->messengerRepository->find($messengerDto['messenger']['id']); // todo: переделать под dto
            $messenger = new OrganizationMessenger();
            $messenger->setValue($messengerDto['value']);
            $messenger->setOrganization($organization);
            $messenger->setMessenger($messengerType);
            $organization->addOrganizationMessenger($messenger);
        }

        $this->repository->save($organization);

        return $this->serializerOrganization($organization);
    }

    public function needToCreate(string $inn): bool
    {
        if (!empty($this->repository->findOneBy(['inn' => $inn]))) {
            return false;
        }

        return true;
    }

    public function getDadata(string $inn)
    {
        $response = $this->client->request(
            'GET',
            self::DADATA_FIND_BY_INN_URI,
            [
                'query' => [
                    'query' => $inn
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Token ' . $this->dadataKey
                ]
            ]
        );

        $dadata = json_decode($response->getContent(), true)['suggestions'];

        return !empty($dadata) ? $dadata['0'] : [];
    }

    public function update(OrganizationDto $dto, User $owner, array $messengers = []): OrganizationDto
    {
        if (!empty($dto->inn)) {
            $organization = $this->repository->findOneBy(['inn' => $dto->inn]);
        }

        if (!empty($dto->id)) {
            $organization = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (empty($organization)) {
            throw new Exception($this->translator->trans('organization_could_not_be_found'));
        }

        if($owner->getId() != $organization->getOwner()->getId()) {
            throw new Exception($this->translator->trans('organization_does_not_belong_to_you'));
        }

        $publicType = !empty($dto->typePublic) ? $this->organizationTypeRepository->find($dto->typePublic->id) : null;

        $organization->setDescription($dto->description ?? $organization->getDescription());
        $organization->setTypePublic($publicType ?? $organization->getTypePublic());
        $organization->setPhone($dto->phone ?? $organization->getPhone());
        $organization->setSite($dto->site ?? $organization->getSite());

        if (!empty($dto->messengers)) {
            foreach ($organization->getOrganizationMessengers() as $messenger) {
                $this->organizationMessengerRepository->remove($messenger);
            }

            foreach($dto->messengers as $messengerDto){
                if (empty($messengerDto['value'])) {
                    continue;
                }

                $messengerType = $this->messengerRepository->find($messengerDto['messenger']['id']); // todo: переделать под dto
                $messenger = new OrganizationMessenger();
                $messenger->setValue($messengerDto['value']);
                $messenger->setOrganization($organization);
                $messenger->setMessenger($messengerType);
                $organization->addOrganizationMessenger($messenger);
            }
        }

        $this->repository->save($organization);

        foreach ($organization->getOrganizationMessengers() as $organizationMessenger) {
            $messengers[] = $this->serializerMessengers($organizationMessenger, ['organization']);
        }

        return $this->serializerOrganization($organization, ['messengers'], [], ['messengers' => $messengers]);
    }

    public function remove(Organization $organization): void
    {
        $this->repository->remove($organization);
    }

    public function delete(OrganizationDto $dto, User $owner): OrganizationDto
    {
        if (!empty($dto->inn)) {
            $organization = $this->repository->findOneBy(['inn' => $dto->inn]);
        }

        if (!empty($dto->id)) {
            $organization = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (empty($organization)) {
            throw new Exception($this->translator->trans('organization_could_not_be_found'));
        }

        if($owner->getId() != $organization->getOwner()->getId()) {
            throw new Exception($this->translator->trans('organization_does_not_belong_to_you'));
        }

        $organization->setDeleted(1);
        $this->repository->save($organization);

        return new OrganizationDto(
            id: $organization->getId(),
            deleted: $organization->isDeleted()
        );
    }

    public function create(OrganizationDto $dto, User $owner): OrganizationDto
    {
        if ($this->repository->findOneBy(['inn' => $dto->inn]) || $this->repository->findOneBy(['phone' => $dto->phone])) {
            throw new Exception($this->translator->trans('organization_already_exists'), 500);
        }

        $organization = new Organization();
        $organization->setCreatedAt(new DateTimeImmutable());
        $publicType = !empty($dto->typePublic) ? $this->organizationTypeRepository->find($dto->typePublic->id) : null;
        $country = $this->countryRepository->findOneBy(['id' => $dto->country->id]);

        if ($dto->country->id == $this->countryRepository->findOneBy(['name' => "Россия"])->getId()) {
            $dadata =  $this->getDadata($dto->inn);
            $name = empty($dadata) ? null : $dadata['data']['name']['full_with_opf'];
            $shortName = empty($dadata) ? null : $dadata['data']['name']['short_with_opf'];
            $legalAddress = empty($dadata) ? null : $dadata['data']['address']['value'];
        }else{
            $name = empty($dto->name) ? null : $dto->name;
            $shortName = empty($shortName) ? null : $shortName;
            $legalAddress = empty($dto->legalAddress) ? null : $dto->legalAddress;
        }

        $organization->setInn($dto->inn);
        $organization->setName($name);
        $organization->setShortName($shortName);
        $organization->setLegalAddress($legalAddress);
        $organization->setDescription($dto->description);
        $organization->setTypePublic($publicType);
        $organization->setPhone($dto->phone);
        $organization->setSite($dto->site);
        $organization->setCountry($country);
        $organization->setOwner($owner);

        foreach ($dto->messengers as $messengerDto) {
            $messengerType = $this->messengerRepository->find($messengerDto['messenger']['id']);  // todo: переделать под dto
            $messenger = new OrganizationMessenger();
            $messenger->setValue($messengerDto['value']);
            $messenger->setOrganization($organization);
            $messenger->setMessenger($messengerType);
            $organization->addOrganizationMessenger($messenger);
        }

        $this->repository->save($organization);

        return $this->serializerOrganization($organization);
    }

    public function getOrganization(OrganizationDto $dto): OrganizationDto
    {
        if (!empty($dto->inn)) {
            $organization = $this->repository->findOneBy(['inn' => $dto->inn]);
        }

        if (!empty($dto->id)) {
            $organization = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (empty($organization)) {
            throw new Exception($this->translator->trans('organization_could_not_be_found'));
        }

        return $this->serializerOrganization($organization);
    }

    public function getCollection(): array
    {
        $arrayOrganizationDto = [];

        $organizations = $this->repository->findBy([
            'deleted' => 0
        ]);

        foreach ($organizations as $organization) {
            $arrayOrganizationDto[] = $this->serializerOrganization($organization);
        }

        return $arrayOrganizationDto;
    }

    public function serializerOrganization(Organization $organization, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = []): OrganizationDto
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);

        $normalize = array_merge($this->serializer->normalize($organization, null, $condition), $addAttribute);

        $dto = $this->serializer->denormalize(
            $normalize, OrganizationDto::class
        );

        return $dto;
    }

    public function serializerMessengers(OrganizationMessenger $organizationMessenger, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = []): OrganizationMessengerDto
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);

        $normalize = array_merge($this->serializer->normalize($organizationMessenger, null, $condition), $addAttribute);

        return $this->serializer->denormalize(
            $normalize, OrganizationMessengerDto::class
        );
    }
}
