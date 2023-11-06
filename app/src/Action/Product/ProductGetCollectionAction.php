<?php

namespace App\Action\Product;

use App\Dto\Product\ProductCollectionDto;
use App\Dto\Product\Request\RequestGetCollectionDto;
use App\Service\ProductService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class ProductGetCollectionAction
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function handle(PaginatorInterface $paginator, RequestGetCollectionDto $dto): ProductCollectionDto
    {
        return $this->service->getCollection($paginator, $dto);
    }
}
