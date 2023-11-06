<?php

namespace App\Action\Country;

use App\Dto\Country\CountryDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Service\CountryService;
use App\Service\OrganizationTypeService;

class CountryGetCollectionAction
{
    public function __construct(
        private readonly CountryService $service
    )
    {
    }

    public function handle(): array
    {
        $result = [];
        $data = $this->service->getCollection();
        if (!empty($data)) {
            foreach ($data as $item) {
                $result[] = (new CountryDto(
                    id: $item->getId(),
                    name: $item->getName(),
                    shortName: $item->getShortName(),
                ))->toArray();
            }
        }
        return $result;
    }
}