<?php

namespace App\Dto\Appeal\Filter;

use App\Dto\Appeal\AppealCategory\AppealCategoryDto;
use App\Dto\Appeal\AppealStatus\AppealStatusDto;
use App\Dto\BasicDto;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

class FilterDto extends BasicDto
{
    public function __construct(
        public readonly ?string             $query = null,
        public readonly ?AppealCategoryDto  $category = null,
        public readonly ?AppealStatusDto    $status  = null,
        public readonly ?int                $page = null,
        public readonly ?int                $limit = null,
    )
    {
    }
}
