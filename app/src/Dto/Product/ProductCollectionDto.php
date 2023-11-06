<?php

namespace App\Dto\Product;

use App\Dto\BasicDto;
use App\Dto\Product\Filter\FilterDto;
use App\Dto\Product\Pagination\PaginationDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductCollectionDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: ProductDto::class)))]
        public readonly array               $products = [],

        #[Groups(['search'])]
        public readonly ?PaginationDto      $pagination = null,

        #[Groups(['search'])]
        public readonly ?FilterDto          $filter = null,
    )
    {
    }
}
