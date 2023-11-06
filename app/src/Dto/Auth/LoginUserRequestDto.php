<?php
namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginUserRequestDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        public readonly string $password
    )
    {
    }
}