<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\Organization\OrganizationDto;
use App\Dto\User\UserDto;


class RegisterFinalDto extends BasicDto
{
    public function __construct(
        public readonly UserDto $user,
        public readonly OrganizationDto $organization
    )
    {
    }
}