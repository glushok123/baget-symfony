<?php

namespace App\Action\Organization;

use App\Dto\Organization\OrganizationDto;
use App\Service\OrganizationService;

class OrganizationGetAction
{
    public function __construct(
        private readonly OrganizationService $service
    )
    {
    }

    public function handle(OrganizationDto $dto): OrganizationDto
    {
        return $this->service->getOrganization($dto);
    }
}