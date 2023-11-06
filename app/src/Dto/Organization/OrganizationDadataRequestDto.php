<?php

namespace App\Dto\Organization;

use Symfony\Component\Validator\Constraints as Assert;

class OrganizationDadataRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $inn
    )
    {
    }
}