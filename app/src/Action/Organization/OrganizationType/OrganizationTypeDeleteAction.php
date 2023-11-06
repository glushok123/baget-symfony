<?php

namespace App\Action\Organization\OrganizationType;

use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Service\OrganizationMessengerService;
use App\Service\OrganizationTypeService;

class OrganizationTypeDeleteAction
{
    public function __construct(
        private readonly OrganizationTypeService $service
    )
    {
    }

    public function handle(OrganizationTypeDto $dto): OrganizationTypeDto
    {
       return $this->service->delete($dto);
    }
}