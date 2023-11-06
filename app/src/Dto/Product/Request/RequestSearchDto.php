<?php

namespace App\Dto\Product\Request;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class RequestSearchDto extends BasicDto
{
    public function __construct(
        public readonly ?string             $query = null,
        public readonly ?array              $category = [],
        public readonly ?array              $type = [],
        public readonly ?array              $brand = [],
        public readonly ?int                $page = null,
        public readonly ?int                $limit = null,
        public readonly ?int                $min_price = null,
        public readonly ?int                $max_price = null,

    )
    {
    }
}
