<?php

namespace App\Action\Appeal;

use App\Dto\Appeal\AppealDto;
use App\Dto\User\UserDto;
use App\Entity\User\User;
use App\Service\AppealService;
use App\Service\UserService;

class AppealCreateAction
{
    public function __construct(
        private readonly AppealService $service
    )
    {
    }

    public function handle(AppealDto $dto, ?User $user): AppealDto
    {
        return $this->service->create($dto, $user);
    }
}
