<?php

namespace App\Action\Product;

use App\Dto\Product\Filter\FilterDto;
use App\Dto\Product\ProductDto;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductGetFilterAction
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function handle(): FilterDto
    {
        return $this->service->getProductFilter();
    }
}
