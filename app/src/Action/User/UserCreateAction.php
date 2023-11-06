<?php

namespace App\Action\User;

use App\Dto\User\UserDto;
use App\Service\UserService;

class UserCreateAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(UserDto $dto): UserDto
    {
        return $this->service->create($dto);
    }
}