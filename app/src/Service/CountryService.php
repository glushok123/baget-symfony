<?php

namespace App\Service;

use App\Dto\Country\CountryDto;
use App\Entity\Country;
use App\Repository\CountryRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class CountryService
{
    public function __construct(
        private readonly CountryRepository $repository,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function create(CountryDto $dto): CountryDto
    {
        $country = new Country();
        $country->setName($dto->name ?? '');
        $country->setShortName($dto->shortName ?? '');
        $country->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($country);

        return new CountryDto(
            id: $country->getId(),
            name: $country->getName(),
            shortName: $country->getShortName(),
        );
    }

    public function getCollection(): array
    {
        return $this->repository->findAll();
    }

    public function update(CountryDto $dto): CountryDto
    {
        $country = $this->repository->findOneBy(['id' => $dto->id]);
        if (empty($country)) {
            throw new Exception($this->translator->trans('country_could_not_be_found'));
        }
        $country->setName($dto->name ?? '');
        $country->setShortName($dto->shortName ?? '');
        $country->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($country);

        return new CountryDto(
            id: $country->getId(),
            name: $country->getName(),
            shortName: $country->getShortName(),
        );
    }
}