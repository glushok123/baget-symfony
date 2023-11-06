<?php

namespace App\Action\Country;

use App\Dto\Country\CountryDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Service\CountryService;
use App\Service\OrganizationTypeService;

class CountryUpdateAction
{
    public function __construct(
        private readonly CountryService $service
    )
    {
    }

    public function handle(CountryDto $dto): CountryDto
    {
       return $this->service->update($dto);
    }
}