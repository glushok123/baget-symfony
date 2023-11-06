<?php

namespace App\Action\Auth;

use App\Dto\User\UserDto;
use App\Dto\Auth\UserRecoveryPasswordDto;
use App\Service\UserService;

class RecoveryPasswordUserAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(UserRecoveryPasswordDto $dto): UserDto
    {
        return $this->service->changePasswordByHashUser($dto);
    }
}