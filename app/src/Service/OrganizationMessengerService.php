<?php

namespace App\Service;

use App\Dto\Messenger\MessengerDto;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Entity\Organization\OrganizationMessenger;
use App\Repository\MessengerRepository;
use App\Repository\OrganizationMessengerRepository;
use App\Repository\OrganizationRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationMessengerService
{
    public function __construct(
        private readonly OrganizationMessengerRepository    $repository,
        private readonly MessengerRepository                $messengerRepository,
        private readonly TranslatorInterface                $translator,
        private readonly OrganizationRepository             $organizationRepository,
    )
    {
    }

    public function create(OrganizationMessengerDto $dto): OrganizationMessengerDto
    {
        $messenger = new OrganizationMessenger();
        $messengerType = $dto->messenger->id ? $this->messengerRepository->find($dto->messenger->id) : null;
        $messenger->setValue($dto->value ?? '');
        $messenger->setMessenger($messengerType);
        $messenger->setOrganization($this->organizationRepository->findOneBy(['id' => $dto->organization->id]));
        $messenger->setDeleted(0);
        $messenger->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($messenger);

        return new OrganizationMessengerDto(
            messenger: new MessengerDto(
                id: $messenger->getMessenger()->getId()
            ),
            id: $messenger->getId(),
            value: $messenger->getValue(),
            deleted: $messenger->getDeleted()
        );
    }

    public function getCollection(): array
    {
        return $this->repository->findBy([
            'deleted' => 0
        ]);
    }

    public function update(OrganizationMessengerDto $dto): OrganizationMessengerDto
    {
        $messenger = $this->repository->find($dto->id);
        if (empty($messenger)) {
            throw new Exception($this->translator->trans('messenger_could_not_be_found'));
        }
        $messenger->setValue($dto->value ?? $messenger->getValue());
        $messenger->setDeleted($dto->deleted ?? $messenger->getDeleted());
        $messenger->setMessenger($dto->messenger ?? $messenger->getMessenger());
        $messenger->setUpdatedAt(new DateTimeImmutable());
        $this->repository->save($messenger);

        return new OrganizationMessengerDto(
            messenger: $messenger->getMessenger(),
            id: $messenger->getId(),
            value: $messenger->getValue(),
            deleted: $messenger->getDeleted()
        );
    }

    public function delete($dto): OrganizationMessengerDto
    {
        $messenger = $this->repository->find($dto->id);
        if (empty($messenger)) {
            throw new Exception($this->translator->trans('messenger_could_not_be_found'));
        }
        $messenger->setDeleted(1);
        $this->repository->save($messenger);

        return new OrganizationMessengerDto(
            messenger: $messenger->getMessenger(),
            id: $messenger->getId(),
            value: $messenger->getValue(),
            deleted: $messenger->getDeleted()
        );
    }
}
