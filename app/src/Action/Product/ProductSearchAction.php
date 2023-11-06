<?php

namespace App\Action\Product;

use App\Dto\Product\ProductCollectionDto;
use App\Dto\Product\Request\RequestSearchDto;
use App\Service\ProductService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductSearchAction
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function handle(PaginatorInterface $paginator, RequestSearchDto $dto): ProductCollectionDto
    {
        return $this->service->getProductSearch($paginator, $dto);
    }
}
