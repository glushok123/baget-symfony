<?php

namespace App\Dto\Product\Image;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class ImageDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        public readonly ?string             $resolvedPathBig = null,

        #[Groups(['search'])]
        public readonly ?string             $resolvedPathSmall = null,

        #[Groups(['search'])]
        public readonly ?string             $resolvedPathMedium = null,
    )
    {
    }
}
