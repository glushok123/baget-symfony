<?php

namespace App\Dto\Product\Brand;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class BrandDto extends BasicDto
{
    public function __construct(
        #[Groups(['filter', 'search'])]
        public readonly ?int                $id = null,
        public readonly ?int                $externalId = null,

        #[Groups(['filter', 'search'])]
        public readonly ?string             $name = null,
        public readonly ?bool               $deleted = null,
        public readonly ?bool               $active = null,
    )
    {
    }
}
