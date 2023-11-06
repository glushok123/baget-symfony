<?php

namespace App\Action;

use App\Dto\Organization\OrganizationDadataRequestDto;
use App\Dto\Organization\OrganizationDadataResponseDto;
use App\Service\OrganizationService;

class OrganizationDadataRequestAction
{
    public function __construct(
        private readonly OrganizationService $service
    )
    {
    }

    public function handle(OrganizationDadataRequestDto $dto): OrganizationDadataResponseDto
    {
        return new OrganizationDadataResponseDto(
            dadata: $this->service->getDadata($dto->inn)
        );
    }
}