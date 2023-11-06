<?php

namespace App\Dto\Organization\OrganizationAddress;

use App\Dto\BasicDto;
use App\Dto\Country\CountryDto;
use App\Dto\Organization\OrganizationDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class OrganizationAddressDto extends BasicDto
{
    public function __construct(
        #[Groups(['update_address', 'address_id'])]
        #[Assert\NotBlank(groups: ['update_address'])]
        public readonly ?int        $id = null,

        #[Groups(['create_address', 'update_address'])]
        #[Assert\NotBlank(message: 'Значение Страны не должно быть пустым', groups: ['create_address', 'update_address'])]
        public readonly ?CountryDto $country = null,

        #[Groups(['create_address', 'update_address'])]
        #[Assert\NotBlank(message: 'Значение Область/Регион не должно быть пустым', groups: ['create_address', 'update_address'])]
        public readonly ?string     $region = null,

        #[Groups(['create_address', 'update_address'])]
        #[Assert\NotBlank(message: 'Значение Город/Населенный пункт не должно быть пустым', groups: ['create_address', 'update_address'])]
        public readonly ?string     $city = null,

        #[Groups(['create_address', 'update_address'])]
        #[Assert\NotBlank(message: 'Значение Улица не должно быть пустым', groups: ['create_address', 'update_address'])]
        public readonly ?string     $street = null,

        #[Groups(['create_address', 'update_address'])]
        #[Assert\NotBlank(message: 'Значение Здание/Сооружение/Дом не должно быть пустым', groups: ['create_address', 'update_address'])]
        public readonly ?string     $house = null,

        #[Groups(['create_address', 'update_address'])]
        public readonly ?string     $apartment = null,

        #[Groups(['deleted_address'])]
        public readonly ?bool       $deleted = null,

        #[Groups(['create_address'])]
        #[Assert\NotBlank(groups: ['create_address'])]
        public ?OrganizationDto     $organization = null,
    )
    {
    }
}
