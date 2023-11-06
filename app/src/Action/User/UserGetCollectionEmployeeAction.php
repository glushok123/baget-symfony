<?php

namespace App\Action\User;

use App\Entity\User\User;
use App\Service\UserService;

class UserGetCollectionEmployeeAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(User $user): array
    {
        return $this->service->getCollectionEmployee($user);
    }
}
