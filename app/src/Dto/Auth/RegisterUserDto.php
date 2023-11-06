<?php

namespace App\Dto\Auth;

use DateTimeImmutable;
use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class RegisterUserDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Groups(['registration'])]
        public readonly string              $name,

        #[Assert\NotBlank]
        #[Groups(['registration'])]
        public readonly string              $surname,

        #[Assert\NotBlank,
        Assert\Email]
        #[Groups(['registration'])]
        public readonly string              $email,

        #[OA\Property(type: 'string', example: '+79991234567')]
        #[PhoneNumberAssert]
        #[Groups(['registration'])]
        public readonly PhoneNumber         $phone,

        #[Assert\NotBlank]
        #[Groups(['registration'])]
        public readonly string              $password,

        #[Groups(['registration'])]
        public readonly ?bool               $sex,

        #[Groups(['registration'])]
        public readonly ?string             $middleName = null,

        #[Groups(['registration'])]
        public readonly ?DateTimeImmutable  $birthday = null
    )
    {
    }
}
