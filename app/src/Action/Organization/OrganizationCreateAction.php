<?php

namespace App\Action\Organization;

use App\Dto\Organization\OrganizationDto;
use App\Entity\User\User;
use App\Repository\CountryRepository;
use App\Service\OrganizationService;
use App\Service\UserService;
use Exception;

class OrganizationCreateAction
{
    public function __construct(
        private readonly UserService         $userService,
        private readonly OrganizationService $service,
        private readonly CountryRepository   $countryRepository
    )
    {
    }

    public function handle(OrganizationDto $dto, User $user): OrganizationDto
    {
        if (!$this->service->needToCreate($dto->inn)) {
            throw new Exception("Organization already exists");
        }

        return $this->service->create($dto, $user);
    }
}
