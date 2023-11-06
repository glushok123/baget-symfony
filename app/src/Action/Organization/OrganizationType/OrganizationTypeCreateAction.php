<?php

namespace App\Action\Organization\OrganizationType;

use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Service\OrganizationTypeService;

class OrganizationTypeCreateAction
{
    public function __construct(
        private readonly OrganizationTypeService $service
    )
    {
    }

    public function handle(OrganizationTypeDto $dto): OrganizationTypeDto
    {
        return $this->service->create($dto);
    }
}