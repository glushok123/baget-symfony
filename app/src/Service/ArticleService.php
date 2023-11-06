<?php

namespace App\Service;

use App\Dto\Article\ArticleDto;
use App\Repository\ArticleRepository;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $repository,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function getCollection(): array
    {
        $articles = [];
        $articlesEntities = $this->repository->findBy([
            'active' => 1,
            'deleted' => 0
        ]);
        foreach ($articlesEntities as $item) {
            $articles[] = new ArticleDto(
                id: $item->getId(),
                name: $item->getName(),
                slug: $item->getSlug(),
                previewText: $item->getPreviewText(),
                detailText: $item->getDetailText(),
                previewPicture: $item->getPreviewPicture(),
                detailPicture: $item->getDetailPicture(),
                active: $item->isActive(),
                deleted: $item->isDeleted(),
                publishedAt: $item->getPublishedAt()
            );
        }

        return $articles;
    }

    public function get(ArticleDto $dto): ArticleDto
    {
        if (empty($dto->slug)) {
            throw new Exception($this->translator->trans('article_could_not_be_found'));
        }

        $article = $this->repository->findOneBy([
            'slug' => $dto->slug,
            'active' => 1,
            'deleted' => 0
        ]);

        if (empty($article)) {
            throw new Exception($this->translator->trans('article_could_not_be_found'));
        }

        return new ArticleDto(
            id: $article->getId(),
            name: $article->getName(),
            previewText: $article->getPreviewText(),
            detailText: $article->getDetailText(),
            previewPicture: $article->getPreviewPicture(),
            detailPicture: $article->getDetailPicture(),
            publishedAt: $article->getPublishedAt()
        );
    }
}
