<?php

namespace App\Action\User;

use App\Dto\User\UserDto;
use App\Entity\User\User;
use App\Service\UserService;

class UserGetByTokenAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(?User $user): UserDto
    {
        return $this->service->getUserByToken($user);
    }
}
