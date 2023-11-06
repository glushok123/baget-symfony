<?php

namespace App\Controller;

use App\Action\Article\ArticleGetAction;
use App\Action\Article\ArticleGetCollectionAction;
use App\Dto\Article\ArticleDto;
use App\Dto\Organization\OrganizationAddress\OrganizationAddressDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Attributes as OA;

class ArticleController extends ApiController
{
    public function __construct(
        private readonly ArticleGetCollectionAction $getCollectionAction,
        private readonly ArticleGetAction $getAction
    )
    {
    }

    #[OA\Response(
        response: 200,
        description: 'Получение новостей',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: ArticleDto::class)))
    )]
    #[OA\Tag(name: 'Article')]
    #[Route('api/article/get-collection', name: 'app_article_collection', methods: ['GET'])]
    public function getCollection(): Response
    {
        try {
            $data = $this->getCollectionAction->handle();
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: ArticleDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение новости по slug',
        content: new Model(type: ArticleDto::class)
    )]
    #[OA\Tag(name: 'Article')]
    #[Route('api/article/', name: 'app_article', methods: ['GET'])]
    public function get(#[MapRequestPayload] ArticleDto $dto): Response
    {
        try {
            $data = $this->getAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
