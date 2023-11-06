<?php

namespace App\Dto\Product\Request;

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

class RequestGetCollectionDto extends BasicDto
{
    public function __construct(
        public readonly ?int                $page = null,
        public readonly ?int                $limit = null,
        public readonly ?int                $category = null,
        public readonly ?int                $type = null,
        public readonly ?int                $brand = null,
        public readonly ?int                $model = null,
        public readonly ?int                $min_price = null,
        public readonly ?int                $max_price = null,
    )
    {
    }
}
