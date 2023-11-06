<?php

namespace App\Action\User;

use App\Dto\User\UserDto;
use App\Entity\User\User;
use App\Service\UserService;

class UserUpdateEmployeeAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(UserDto $dto, User $user): UserDto
    {
       return $this->service->updateEmployee($dto, $user);
    }
}
