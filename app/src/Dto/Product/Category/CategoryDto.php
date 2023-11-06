<?php

namespace App\Dto\Product\Category;

use App\Dto\BasicDto;
use App\Dto\Product\Type\TypeDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;

class CategoryDto extends BasicDto
{
    public function __construct(
        #[Groups(['filter', 'search'])]
        public readonly ?int                $id = null,

        public readonly ?int                $externalId = null,
        public readonly ?int                $sortingNumber = null,

        #[Groups(['filter', 'search'])]
        public readonly ?string             $name = null,
        public readonly ?bool               $deleted = null,
        public readonly ?bool               $active = null,

        #[Groups(['filter'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: TypeDto::class)))]
        public readonly ?array              $productTypes = [],
    )
    {
    }
}
