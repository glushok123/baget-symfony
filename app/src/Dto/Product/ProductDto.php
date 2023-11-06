<?php

namespace App\Dto\Product;

use App\Dto\BasicDto;
use App\Dto\Product\Brand\BrandDto;
use App\Dto\Product\Category\CategoryDto;
use App\Dto\Product\Image\ImageDto;
use App\Dto\Product\Model\ModelDto;
use App\Dto\Product\Price\PriceDto;
use App\Dto\Product\Type\TypeDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        public readonly ?int                $id = null,
        public readonly ?int                $externalId = null,
        public readonly ?int                $sortingNumber = null,

        #[Groups(['search'])]
        public readonly ?string             $name = null,

        #[Groups(['search'])]
        public readonly ?string             $article = null,
        public readonly ?bool               $colorBlack = null,
        public readonly ?bool               $colorMagenta = null,
        public readonly ?bool               $colorYellow = null,
        public readonly ?bool               $colorCyan = null,
        public readonly ?bool               $colorWhite = null,
        public readonly ?bool               $colorTransparent = null,
        public readonly ?bool               $formatA0 = null,
        public readonly ?bool               $formatA1 = null,
        public readonly ?bool               $formatA2 = null,
        public readonly ?bool               $formatA3 = null,
        public readonly ?bool               $formatA4 = null,
        public readonly ?bool               $deleted = null,
        public readonly ?bool               $active = null,

        #[Groups(['search'])]
        public readonly ?int                $count = null,
        public readonly ?int                $countTransit = null,

        #[Groups(['search'])]
        public readonly ?int                $minCount = null,

        public readonly ?CategoryDto        $category = null,
        public readonly ?BrandDto           $brand = null,
        public readonly ?ModelDto           $model = null,
        public readonly ?TypeDto            $type = null,

        #[Groups(['search'])]
        public readonly ?ImageDto           $image = null,

        #[Groups(['search'])]
        public readonly ?PriceDto           $price = null,

        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: ProductDto::class)))]
        public readonly array               $parent = [],

        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: ProductDto::class)))]
        public readonly array               $children = [],
    )
    {
    }
}
