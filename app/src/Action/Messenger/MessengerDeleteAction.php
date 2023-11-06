<?php

namespace App\Action\Messenger;

use App\Dto\Messenger\MessengerDto;
use App\Service\MessengerService;

class MessengerDeleteAction
{
    public function __construct(
        private readonly MessengerService $service
    )
    {
    }

    public function handle(MessengerDto $dto): MessengerDto
    {
       return $this->service->delete($dto);
    }
}