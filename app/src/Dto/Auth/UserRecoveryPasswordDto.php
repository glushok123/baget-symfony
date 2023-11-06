<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use Symfony\Component\Validator\Constraints as Assert;

class UserRecoveryPasswordDto extends BasicDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $password,

        #[Assert\NotBlank]
        public readonly string $passwordConfirm,

        #[Assert\NotBlank]
        public readonly string $hash
    )
    {
    }
}
