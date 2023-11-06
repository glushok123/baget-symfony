<?php

namespace App\Action\User;

use App\Dto\User\UserChangePasswordDto;
use App\Dto\User\UserDto;
use App\Entity\User\User;
use App\Service\UserService;

class UserChangePasswordAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(UserChangePasswordDto $dto, ?User $user): UserDto
    {
       return $this->service->changePasswordUser($dto, $user);
    }
}
