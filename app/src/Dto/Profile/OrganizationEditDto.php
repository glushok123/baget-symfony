<?php

namespace App\Dto\Profile;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use App\Entity\Organization\OrganizationType;
use App\Validator\Inn;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Validator\Constraints as Assert;

class OrganizationEditDto extends BasicDto implements DtoInterface
{
    public function __construct(

        #[Inn('strict')]
        public readonly string      $inn,

        #[Assert\NotBlank]
        public readonly string           $organizationName,

        #[Assert\NotBlank]
        public readonly string           $legalAddress,

        public readonly string           $description,

        #[Assert\NotBlank]
        public readonly OrganizationType $publicType,

        #[PhoneNumberAssert]
        public readonly PhoneNumber      $organizationPhone,

        #[Assert\NotBlank]
        public readonly string           $site,

        public readonly array            $messengers
    )
    {
    }
}
