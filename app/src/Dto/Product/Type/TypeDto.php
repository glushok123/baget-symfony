<?php

namespace App\Dto\Product\Type;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class TypeDto extends BasicDto
{
    public function __construct(
        #[Groups(['search'])]
        public readonly ?int                $id = null,

        public readonly ?int                $externalId = null,
        public readonly ?int                $sortingNumber = null,

        #[Groups(['search'])]
        public readonly ?string             $name = null,
        public readonly ?bool               $deleted = null,
        public readonly ?bool               $active = null,
    )
    {
    }
}
