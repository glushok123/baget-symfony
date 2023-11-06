<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\Country\CountryDto;
use App\Dto\DtoInterface;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use libphonenumber\PhoneNumber;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

class RegisterOrganizationDto extends BasicDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Groups(['registration'])]
        public readonly string $inn,

        #[Assert\NotBlank]
        #[PhoneNumberAssert]
        #[OA\Property(type: 'string', example: '+79991234567')]
        #[Groups(['registration'])]
        public readonly PhoneNumber $phone,

        #[Assert\NotBlank]
        #[Groups(['registration'])]
        public readonly CountryDto $country,

        #[OA\Property(type: 'array',items: new OA\Items(ref: new Model(type: OrganizationMessengerDto::class)))]
        #[Groups(['registration'])]
        public readonly array $messengers = [],

        #[Groups(['registration'])]
        public readonly ?OrganizationTypeDto $publicType = null,

        #[Groups(['registration'])]
        public readonly ?string     $name = null,

        #[Groups(['registration'])]
        public readonly ?string     $legalAddress = null,

        #[Groups(['registration'])]
        public readonly ?string     $description = null,

        #[Groups(['registration'])]
        public readonly ?string     $site = null,
    )
    {
    }
}
