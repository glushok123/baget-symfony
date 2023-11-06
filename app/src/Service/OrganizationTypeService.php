<?php

namespace App\Service;

use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Entity\Organization\OrganizationType;
use App\Repository\OrganizationTypeRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationTypeService
{
    public function __construct(
        private readonly OrganizationTypeRepository $repository,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function create(OrganizationTypeDto $dto): OrganizationTypeDto
    {
        $type = new OrganizationType();
        $type->setActive($dto->active ?? '');
        $type->setName($dto->name ?? '');
        $type->setDeleted(0);
        $type->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($type);

        return new OrganizationTypeDto(
            id: $type->getId(),
            active: $type->isActive(),
            name: $type->isDeleted(),
            deleted: $type->isDeleted()
        );
    }

    public function getCollection(): array
    {
        return $this->repository->findBy([
            'deleted' => 0
        ]);
    }

    public function update(OrganizationTypeDto $dto): OrganizationTypeDto
    {
        $type = $this->repository->find($dto->id);
        if (empty($type)) {
            throw new Exception($this->translator->trans('organization_type_could_not_be_found'));
        }
        $type->setActive($dto->active ?? '');
        $type->setName($dto->name ?? '');
        $type->setDeleted(0);
        $type->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($type);

        return new OrganizationTypeDto(
            id: $type->getId(),
            active: $type->isActive(),
            name: $type->isDeleted(),
            deleted: $type->isDeleted()
        );
    }

    public function delete($dto): OrganizationTypeDto
    {
        $type = $this->repository->find($dto->id);
        if (empty($type)) {
            throw new Exception($this->translator->trans('organization_type_could_not_be_found'));
        }
        $type->setDeleted(1);
        $this->repository->save($type);

        return new OrganizationTypeDto(
            id: $type->getId(),
            active: $type->isActive(),
            name: $type->isDeleted(),
            deleted: $type->isDeleted()
        );
    }
}
