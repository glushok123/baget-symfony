<?php

namespace App\Dto\Appeal\AppealStatus;

use App\Dto\Appeal\ADto;
use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class AppealStatusDto extends BasicDto
{
    public function __construct(
        #[Groups(['appeal'])]
        public readonly ?int                $id = null,

        #[Groups(['appeal'])]
        public readonly ?string             $name = null,
    )
    {
    }
}
