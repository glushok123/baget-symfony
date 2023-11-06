<?php

namespace App\Action\User;

use App\Dto\User\UserDto;
use App\Entity\User\User;
use App\Service\UserService;

class UserDeleteEmployeeAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(UserDto $dto, User $user): UserDto
    {
       return $this->service->deleteEmployee($dto, $user);
    }
}
