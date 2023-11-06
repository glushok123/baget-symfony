<?php

namespace App\Dto\User;

use App\Dto\BasicDto;
use Symfony\Component\Validator\Constraints as Assert;

class UserChangePasswordDto extends BasicDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $oldPassword,

        #[Assert\NotBlank]
        public readonly string $password,

        #[Assert\NotBlank]
        public readonly string $passwordConfirm
    )
    {
    }
}
