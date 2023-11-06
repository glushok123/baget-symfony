<?php

namespace App\Action\Organization\OrganizationAddress;

use App\Dto\Address\AddressDto;
use App\Entity\User\User;
use App\Service\OrganizationAddressService;

class OrganizationAddressGetCollectionAction
{
    public function __construct(
        private readonly OrganizationAddressService $service
    )
    {
    }

    public function handle(?User $user): array
    {
        return $this->service->getCollection($user);
    }
}
