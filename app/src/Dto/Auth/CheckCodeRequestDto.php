<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CheckCodeRequestDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\Email,
        Assert\NotBlank]
        public readonly string $email,

        #[Assert\Length(exactly: 6),
            Assert\NotBlank]
        public readonly string $code
    )
    {
    }

}