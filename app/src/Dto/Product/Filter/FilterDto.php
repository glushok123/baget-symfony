<?php

namespace App\Dto\Product\Filter;

use App\Dto\BasicDto;
use App\Dto\Product\Brand\BrandDto;
use App\Dto\Product\Category\CategoryDto;
use App\Dto\Product\Model\ModelDto;
use App\Dto\Product\Type\TypeDto;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

class FilterDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: CategoryDto::class)))]
        public readonly array               $category = [],

        #[Groups(['search'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: TypeDto::class)))]
        public readonly array               $type = [],

        #[Groups(['search'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: BrandDto::class)))]
        public readonly array               $brand = [],

        #[Groups(['search'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: ModelDto::class)))]
        public readonly array               $model = [],

        #[Groups(['search'])]
        public readonly ?int                $minPrice = null,

        #[Groups(['search'])]
        public readonly ?int                $maxPrice = null,
    )
    {
    }
}
