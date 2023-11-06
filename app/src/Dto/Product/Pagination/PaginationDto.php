<?php

namespace App\Dto\Product\Pagination;

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

class PaginationDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        public readonly ?int                $currentPageNumber = null,

        #[Groups(['search'])]
        public readonly ?int                $numItemsPerPage = null,

        #[Groups(['search'])]
        public readonly ?int                $totalCount = null,
    )
    {
    }
}
