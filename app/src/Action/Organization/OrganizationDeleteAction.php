<?php

namespace App\Action\Organization;

use App\Dto\Organization\OrganizationDto;
use App\Entity\User\User;
use App\Service\OrganizationService;

class OrganizationDeleteAction
{
    public function __construct(
        private readonly OrganizationService $service
    )
    {
    }

    public function handle(OrganizationDto $dto, User $user): OrganizationDto
    {
       return $this->service->delete($dto, $user);
    }
}
