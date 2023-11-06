<?php

namespace App\Dto\Organization\OrganizationMessenger;

use App\Dto\BasicDto;
use App\Dto\Messenger\MessengerDto;
use App\Dto\Organization\OrganizationDto;
use Symfony\Component\Serializer\Annotation\Groups;

class OrganizationMessengerDto extends BasicDto
{
    public function __construct(
        #[Groups(['organization_messenger_id', 'update_organization'])]
        public readonly ?int                $id = null,

        #[Groups(['create_messenger', 'registration', 'create_organization', 'update_organization'])]
        public ?MessengerDto                $messenger = null,

        #[Groups(['create_messenger'])]
        public readonly ?OrganizationDto    $organization = null,

        #[Groups(['create_messenger', 'registration', 'create_organization', 'update_organization'])]
        public readonly ?string             $value = null,
        public readonly ?bool               $deleted = null,
    )
    {
    }
}
