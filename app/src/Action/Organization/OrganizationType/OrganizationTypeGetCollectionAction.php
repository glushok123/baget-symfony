<?php

namespace App\Action\Organization\OrganizationType;

use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Service\OrganizationTypeService;

class OrganizationTypeGetCollectionAction
{
    public function __construct(
        private readonly OrganizationTypeService $service
    )
    {
    }

    public function handle(): array
    {
        $result = [];
        $data = $this->service->getCollection();
        if (!empty($data)) {
            foreach ($data as $item) {
                $result[] = (new OrganizationTypeDto(
                    id: $item->getId(),
                    active: $item->isActive(),
                    name: $item->getName(),
                    deleted: $item->isDeleted(),
                ))->toArray();
            }
        }
        return $result;
    }
}