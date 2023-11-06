<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\Auth\RegisterUserDto;
use App\Dto\Auth\RegisterOrganizationDto;

#[Assert\Cascade]
class RegisterRequestDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\Valid]
        #[Groups(['registration'])]
        public readonly RegisterUserDto         $user,

        #[Assert\Valid]
        #[Groups(['registration'])]
        public readonly RegisterOrganizationDto $organization
    )
    {
    }
}
