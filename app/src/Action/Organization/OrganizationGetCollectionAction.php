<?php

namespace App\Action\Organization;

use App\Dto\Organization\OrganizationDto;
use App\Service\OrganizationService;

class OrganizationGetCollectionAction
{
    public function __construct(
        private readonly OrganizationService $service
    )
    {
    }

    public function handle(): array
    {
        return $this->service->getCollection();
    }
}