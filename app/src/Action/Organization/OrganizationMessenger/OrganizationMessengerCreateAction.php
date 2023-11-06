<?php

namespace App\Action\Organization\OrganizationMessenger;

use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Service\OrganizationMessengerService;

class OrganizationMessengerCreateAction
{
    public function __construct(
        private readonly OrganizationMessengerService $service
    )
    {
    }

    public function handle(OrganizationMessengerDto $dto): OrganizationMessengerDto
    {
        return $this->service->create($dto);
    }
}