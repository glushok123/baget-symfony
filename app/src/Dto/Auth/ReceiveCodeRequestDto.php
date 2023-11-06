<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ReceiveCodeRequestDto extends BasicDto
{
    public function __construct(
        #[Assert\Email,
        Assert\NotBlank]
        public readonly string $email
    )
    {
    }

}