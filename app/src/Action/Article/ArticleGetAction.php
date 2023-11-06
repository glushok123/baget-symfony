<?php

namespace App\Action\Article;

use App\Dto\Article\ArticleDto;
use App\Service\ArticleService;

class ArticleGetAction
{
    public function __construct(
        private readonly ArticleService $service
    )
    {
    }

    public function handle(ArticleDto $dto): ArticleDto
    {
        return $this->service->get($dto);
    }
}