<?php

namespace App\Dto\Organization;

use App\Dto\BasicDto;
use App\Dto\Country\CountryDto;
use App\Dto\User\UserDto;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use App\Dto\Organization\OrganizationAddress\OrganizationAddressDto;
use libphonenumber\PhoneNumber;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class OrganizationDto extends BasicDto
{
    public function __construct(
        #[Groups(['organization_id', 'deleted_organization'])]
        public readonly ?int        $id = null,
        public readonly ?UserDto    $owner = null,
        public readonly ?UserDto    $employee = null,
        public readonly ?bool       $active = null,
        public readonly ?bool       $approved = null,

        #[Groups(['create_organization', 'organization_inn'])]
        public readonly ?string     $inn = null,

        #[Groups(['create_organization'])]
        public readonly ?string     $name = null,

        public readonly ?string     $shortName = null,

        #[Groups(['create_organization'])]
        public readonly ?string     $legalAddress = null,

        #[Groups(['create_organization', 'update_organization'])]
        public readonly ?string     $description = null,

        #[Groups(['create_organization', 'update_organization'])]
        #[OA\Property(type: 'string', example: '+79991234567')]
        public readonly ?PhoneNumber         $phone = null,

        #[Groups(['create_organization', 'update_organization'])]
        public readonly ?string              $site = null,

        #[Groups(['create_organization', 'update_organization'])]
        #[OA\Property(
            type: 'array',
            items: new OA\Items(ref: new Model(type: OrganizationMessengerDto::class))
        )]
        public readonly ?array               $messengers = [],

        #[Groups(['create_organization'])]
        public readonly ?CountryDto          $country = null,

        #[Groups(['create_organization', 'update_organization'])]
        public readonly ?OrganizationTypeDto $typePublic = null,

        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationAddressDto::class)))]
        public readonly array              $organizationAddresses = [],



        #[Groups(['deleted_address'])]
        public readonly ?bool                $deleted = null
    )
    {
    }
}
