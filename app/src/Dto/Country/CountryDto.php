<?php

namespace App\Dto\Country;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class CountryDto extends BasicDto
{
    public function __construct(

        #[Groups(['country_id', 'registration', 'create_address', 'update_address'])]
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $shortName = null
    )
    {
    }
}
