<?php

namespace App\Dto\Article;

use App\Dto\BasicDto;
use DateTimeImmutable;

class ArticleDto extends BasicDto
{
    public function __construct(
        public readonly ?int               $id = null,
        public readonly ?string            $name = null,
        public readonly ?string            $slug = null,
        public readonly ?string            $previewText = null,
        public readonly ?string            $detailText = null,
        public readonly ?string            $previewPicture = null,
        public readonly ?string            $detailPicture = null,
        public readonly ?bool              $active = null,
        public readonly ?bool              $deleted = null,
        public readonly ?DateTimeImmutable $publishedAt = null
    )
    {
    }
}
