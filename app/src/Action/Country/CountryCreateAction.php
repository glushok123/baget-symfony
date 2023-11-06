<?php

namespace App\Action\Country;

use App\Dto\Country\CountryDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Entity\Country;
use App\Service\CountryService;
use App\Service\OrganizationTypeService;

class CountryCreateAction
{
    public function __construct(
        private readonly CountryService $service
    )
    {
    }

    public function handle(CountryDto $dto): CountryDto
    {
        return $this->service->create($dto);
    }
}