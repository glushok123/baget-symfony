<?php

namespace App\Action\Product;

use App\Dto\Product\ProductDto;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductGetAction
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function handle(ProductDto $dto): ProductDto
    {
        return $this->service->getProduct($dto);
    }
}
