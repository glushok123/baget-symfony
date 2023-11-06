<?php

namespace App\Dto\Organization\OrganizationType;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class OrganizationTypeDto extends BasicDto
{
    public function __construct(
        #[Groups(['organization_type_id', 'registration'])]
        public readonly ?int $id = null,

        public readonly ?bool $active = null,

        #[Groups(['organization_type_name'])]
        public readonly ?string $name = null,

        public readonly ?bool $deleted = null
    )
    {
    }

}
