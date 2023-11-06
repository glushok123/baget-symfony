<?php

namespace App\Action\Organization\OrganizationMessenger;

use App\Dto\Messenger\MessengerDto;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use App\Service\OrganizationMessengerService;

class OrganizationMessengerGetCollectionAction
{
    public function __construct(
        private readonly OrganizationMessengerService $service
    )
    {
    }

    public function handle(): array
    {
        $result = [];
        $data = $this->service->getCollection();
        if (!empty($data)) {
            foreach ($data as $item) {
                $messenger = $item->getMessenger();
                $messengerDto = new MessengerDto(
                    id: $messenger->getId()
                );
                $result[] = (new OrganizationMessengerDto(
                    messenger: $messengerDto,
                    id: $item->getId(),
                    value: $item->getValue(),
                    deleted: $item->getDeleted()
                ))->toArray();
            }
        }
        return $result;
    }
}