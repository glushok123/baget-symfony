<?php

namespace App\Dto\Appeal\AppealMessageFile;

use App\Dto\Appeal\ADto;
use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class AppealMessageFileDto extends BasicDto
{
    public function __construct(
        #[Groups(['appeal'])]
        public readonly ?int                $id = null,

        #[Groups(['create_appeal', 'appeal'])]
        public readonly ?string             $name = null,
    )
    {
    }
}
