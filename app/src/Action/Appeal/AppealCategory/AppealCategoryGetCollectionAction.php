<?php

namespace App\Action\Appeal\AppealCategory;

use App\Dto\Product\ProductCollectionDto;
use App\Dto\Product\Request\RequestGetCollectionDto;
use App\Service\AppealCategoryService;
use Knp\Component\Pager\PaginatorInterface;

class AppealCategoryGetCollectionAction
{
    public function __construct(
        private readonly AppealCategoryService $service
    )
    {
    }

    public function handle(): array
    {
        return $this->service->getCollection();
    }
}
