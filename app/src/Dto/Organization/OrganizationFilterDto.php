<?php

namespace App\Dto\Organization;

use App\Dto\BasicDto;

class OrganizationFilterDto extends BasicDto
{
    public function __construct(
        public readonly ?bool    $withoutEmployee = null,
    )
    {
    }
}
