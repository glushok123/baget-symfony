<?php

namespace App\Action\Auth;

use App\Dto\Auth\LoginUserRequestDto;
use App\Dto\Auth\LoginUserResponseDto;
use App\Service\UserService;

class LoginUserAction
{
    public function __construct(
        private readonly UserService         $userService,
    )
    {
    }

    public function handle(LoginUserRequestDto $dto): LoginUserResponseDto
    {
        $token = $this->userService->loginUser($dto);

        return new LoginUserResponseDto(
            token: $token
        );
    }
}