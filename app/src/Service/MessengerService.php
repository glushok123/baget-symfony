<?php

namespace App\Service;

use App\Dto\Messenger\MessengerDto;
use App\Entity\Messenger;
use App\Repository\MessengerRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class MessengerService
{
    public function __construct(
        private readonly MessengerRepository $repository,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function create(MessengerDto $dto): MessengerDto
    {
        $messenger = new Messenger();
        $messenger->setName($dto->name ?? '');
        $messenger->setActive(1);
        $messenger->setDeleted(0);
        $messenger->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($messenger);

        return new MessengerDto(
            id: $messenger->getId(),
            active: $messenger->isActive(),
            name: $messenger->getName(),
            deleted: $messenger->isDeleted()
        );
    }

    public function getCollection(): array
    {
        return $this->repository->findBy([
            'deleted' => 0,
            'active' => 1
        ]);
    }

    public function update($dto): MessengerDto
    {
        $messenger = $this->repository->find($dto->id);
        if (empty($messenger)) {
            throw new Exception($this->translator->trans('messenger_could_not_be_found'));
        }
        $messenger->setName($dto->name ?? $messenger->getName());
        $messenger->setActive($dto->active ?? $messenger->isActive());
        $messenger->setDeleted($dto->deleted ?? $messenger->isDeleted());
        $messenger->setUpdatedAt(new DateTimeImmutable());
        $this->repository->save($messenger);

        return new MessengerDto(
            id: $messenger->getId(),
            active: $messenger->isActive(),
            name: $messenger->getName(),
            deleted: $messenger->isDeleted()
        );
    }

    public function delete($dto): MessengerDto
    {
        $messenger = $this->repository->find($dto->id);
        if (empty($messenger)) {
            throw new Exception($this->translator->trans('messenger_could_not_be_found'));
        }
        $messenger->setDeleted(1);
        $this->repository->save($messenger);

        return new MessengerDto(
            id: $messenger->getId(),
            active: $messenger->isActive(),
            name: $messenger->getName(),
            deleted: $messenger->isDeleted()
        );
    }
}