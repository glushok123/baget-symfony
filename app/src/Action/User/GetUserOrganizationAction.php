<?php

namespace App\Action\User;

use App\Dto\Organization\OrganizationFilterDto;
use App\Entity\User\User;
use App\Service\UserService;

class GetUserOrganizationAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(?User $user, ?OrganizationFilterDto $organizationFilterDto): array
    {
        return $this->service->getAllOrganizationUser($user, $organizationFilterDto);
    }
}
