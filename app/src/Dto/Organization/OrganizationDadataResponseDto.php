<?php

namespace App\Dto\Organization;

use App\Dto\BasicDto;
use Symfony\Component\Validator\Constraints as Assert;

class OrganizationDadataResponseDto extends BasicDto
{
    #[Assert\NotBlank]
    public function __construct(
        public readonly array $dadata
    )
    {
    }
}