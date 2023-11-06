<?php

namespace App\Dto\Profile;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileEditResponseDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\Valid]
        public readonly UserEditDto         $userDto,

        #[Assert\Valid]
        public readonly OrganizationEditDto $organizationDto
    )
    {
    }
}