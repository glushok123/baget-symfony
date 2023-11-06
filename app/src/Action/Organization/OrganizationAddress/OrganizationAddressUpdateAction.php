<?php

namespace App\Action\Organization\OrganizationAddress;

use App\Dto\Organization\OrganizationAddress\OrganizationAddressDto;
use App\Service\OrganizationAddressService;

class OrganizationAddressUpdateAction
{
    public function __construct(
        private readonly OrganizationAddressService $service
    )
    {
    }

    public function handle(OrganizationAddressDto $dto): OrganizationAddressDto
    {
       return $this->service->update($dto);
    }
}