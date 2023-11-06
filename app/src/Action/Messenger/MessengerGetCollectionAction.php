<?php

namespace App\Action\Messenger;

use App\Dto\Messenger\MessengerDto;
use App\Service\MessengerService;

class MessengerGetCollectionAction
{
    public function __construct(
        private readonly MessengerService $service
    )
    {
    }

    public function handle(): array
    {
        $result = [];
        $data = $this->service->getCollection();
        if (!empty($data)) {
            foreach ($data as $item) {
                $result[] = (new MessengerDto(
                    id: $item->getId(),
                    active: $item->isActive(),
                    name: $item->getName(),
                    deleted: $item->isDeleted()
                ))->toArray();
            }
        }
        return $result;
    }
}