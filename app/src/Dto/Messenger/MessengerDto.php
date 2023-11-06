<?php

namespace App\Dto\Messenger;

use App\Dto\BasicDto;
use Symfony\Component\Serializer\Annotation\Groups;

class MessengerDto extends BasicDto
{
    public function __construct(
        #[Groups(['create_messenger', 'registration', 'create_organization', 'update_organization'])]
        public readonly ?int $id = null,
        public readonly ?bool   $active = null,
        public readonly ?string $name = null,
        public readonly ?bool   $deleted = null
    )
    {
    }
}
