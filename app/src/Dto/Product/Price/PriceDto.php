<?php

namespace App\Dto\Product\Price;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class PriceDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        public readonly ?string             $rubValue = null,

        #[Groups(['search'])]
        public readonly ?string             $usdValue = null,

        public readonly ?string             $rubTransitValue = null,
        public readonly ?string             $usdTransitValue = null,
    )
    {
    }
}
