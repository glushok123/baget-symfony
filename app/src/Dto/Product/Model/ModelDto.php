<?php

namespace App\Dto\Product\Model;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class ModelDto extends BasicDto
{
    public function __construct(
        #[Groups(['filter', 'search'])]
        public readonly ?int                $id = null,

        #[Groups(['filter', 'search'])]
        public readonly ?string             $name = null,

        public readonly ?bool               $deleted = null,
        public readonly ?bool               $active = null,
    )
    {
    }
}
