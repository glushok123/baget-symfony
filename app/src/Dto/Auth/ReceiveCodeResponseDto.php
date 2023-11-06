<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class ReceiveCodeResponseDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $message,

        public readonly ?int $codeReceived
    )
    {
    }

}