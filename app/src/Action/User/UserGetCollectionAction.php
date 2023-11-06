<?php

namespace App\Action\User;

use App\Dto\User\UserDto;
use App\Service\UserService;

class UserGetCollectionAction
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }

    public function handle(): array
    {
        return $this->service->getCollection();
    }
}