<?php

namespace App\Dto\Appeal\AppealCategory;

use App\Dto\Appeal\ADto;
use App\Dto\BasicDto;
use App\Dto\Product\Price\PriceDto;
use App\Dto\User\UserDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class AppealCategoryDto extends BasicDto
{
    public function __construct(
        #[Groups(['create_appeal', 'appeal'])]
        public readonly ?int                $id = null,

        #[Groups(['appeal'])]
        public readonly ?string             $name = null,
    )
    {
    }
}
