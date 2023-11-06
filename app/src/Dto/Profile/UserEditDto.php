<?php

namespace App\Dto\Profile;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use DateTimeImmutable;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UserEditDto extends BasicDto implements DtoInterface
{
    public function __construct(

        #[Assert\NotBlank]
        public readonly string            $surname,

        #[Assert\NotBlank]
        public readonly string            $name,

        public readonly string            $middleName,

        #[PhoneNumberAssert]
        public readonly PhoneNumber       $phone,

        #[Assert\Email,
            Assert\NotBlank]
        public readonly string            $email,

        #[Assert\NotBlank]
        public readonly bool              $sex,

        #[Assert\DateTime]
        public readonly DateTimeImmutable $birthday,

    )
    {
    }
}