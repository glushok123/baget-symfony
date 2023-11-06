<?php

namespace App\Action\Appeal\AppealStatus;

use App\Dto\Product\ProductCollectionDto;
use App\Service\AppealStatusService;;

class AppealStatusGetCollectionAction
{
    public function __construct(
        private readonly AppealStatusService $service
    )
    {
    }

    public function handle(): array
    {
        return $this->service->getCollection();
    }
}
