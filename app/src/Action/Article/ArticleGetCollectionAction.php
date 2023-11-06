<?php

namespace App\Action\Article;

use App\Service\ArticleService;

class ArticleGetCollectionAction
{
    public function __construct(
        private readonly ArticleService $service
    )
    {
    }

    public function handle(): array
    {
        return $this->service->getCollection();
    }
}