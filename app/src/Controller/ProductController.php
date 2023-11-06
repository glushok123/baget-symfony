<?php

namespace App\Controller;

use App\Action\Product\ProductGetCollectionAction;
use App\Action\Product\ProductGetAction;
use App\Action\Product\ProductSearchAction;
use App\Dto\Product\ProductCollectionDto;
use App\Dto\Product\ProductDto;
use App\Dto\Product\Request\RequestGetCollectionDto;
use App\Dto\Product\Request\RequestSearchDto;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
use Throwable;

class ProductController extends ApiController
{
    public function __construct(
        private readonly ProductGetCollectionAction     $getCollectionAction,
        private readonly ProductGetAction               $getAction,
        private readonly ValidatorInterface             $validatorInterface,
        private readonly ProductSearchAction            $getProductSearchAction,
    )
    {
        parent::__construct($this->validatorInterface); //todo: придумать по лучше
    }

    #[Security]
    #[OA\Parameter(
        parameter: "page",
        in:"query",
        name:"page",
        description:"Номер страницы",
        example: 'page=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "limit",
        in:"query",
        name:"limit",
        description:"Кол. товаров на странице",
        example: 'limit=16',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "category",
        in:"query",
        name:"category",
        description:"Категория",
        example: 'category=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "type",
        in:"query",
        name:"type",
        description:"Тип",
        example: 'type=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "brand",
        in:"query",
        name:"brand",
        description:"Бренд",
        example: 'brand=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "model",
        in:"query",
        name:"model",
        description:"Модель",
        example: 'model=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "min_price",
        in:"query",
        name:"min_price",
        description:"Минимальная цена",
        example: 'min_price=1000',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "max_price",
        in:"query",
        name:"max_price",
        description:"Максимальная цена",
        example: 'max_price=16000',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение списка товаров',
        content: new Model(type: ProductCollectionDto::class, groups: ['search'])
    )]
    #[OA\Tag(name: 'Product')]
    #[Route('api/product/get-collection', name: 'app_product_get_collection', methods: ['GET'])]
    public function getCollection(PaginatorInterface $paginator, #[MapQueryString] RequestGetCollectionDto $dto): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle($paginator, $dto);
            return $this->response($data->toArray(['search']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Security]
    #[OA\Parameter(
        parameter: "id",
        in:"query",
        name:"id",
        description:"Id товара",
        example: 'id=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение товара',
        content: new Model(type: ProductDto::class)
    )]
    #[OA\Tag(name: 'Product')]
    #[Route('api/product/get', name: 'app_product_get', methods: ['GET'])]
    public function getProduct(#[MapQueryString] ProductDto $dto): JsonResponse
    {
        try {
            $data = $this->getAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Security]
    #[OA\Get(
        summary: 'Поиск товаров'
    )]
    #[OA\Parameter(
        parameter: "query",
        in:"query",
        name:"query",
        description:"Название",
        example: 'query=Термоузел',
        schema: new OA\Schema(type: "string"),
        required: true
    )]
    #[OA\Parameter(
        parameter: "category[]",
        in:"query",
        name:"category[]",
        description:"Категория",
        example: 'category[]=1',
        schema: new OA\Schema(
            type: "array",
            items: new OA\Items(type: "integer")
        ),
        required: false,
    )]
    #[OA\Parameter(
        parameter: "type[]",
        in:"query",
        name:"type[]",
        description:"Тип",
        example: 'type[]=1',
        schema: new OA\Schema(
            type: "array",
            items: new OA\Items(type: "integer")
        ),
        required: false
    )]
    #[OA\Parameter(
        parameter: "brand[]",
        in:"query",
        name:"brand[]",
        description:"Бренд",
        example: 'brand[]=1',
        schema: new OA\Schema(
            type: "array",
            items: new OA\Items(type: "integer")
        ),
        required: false
    )]
    #[OA\Parameter(
        parameter: "min_price",
        in:"query",
        name:"min_price",
        description:"Минимальная цена",
        example: 'min_price=1000',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "max_price",
        in:"query",
        name:"max_price",
        description:"Максимальная цена",
        example: 'max_price=16000',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "page",
        in:"query",
        name:"page",
        description:"Номер страницы",
        example: 'page=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        parameter: "limit",
        in:"query",
        name:"limit",
        description:"Кол. товаров на странице",
        example: 'limit=16',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Response(
        response: 200,
        description: 'Поиск товаров',
        content: new Model(type: ProductCollectionDto::class, groups: ['search'])
    )]
    #[OA\Tag(name: 'Product')]
    #[Route('api/product/search', name: 'app_product_search', methods: ['GET'])]
    public function getProductSearch(PaginatorInterface $paginator, #[MapQueryString] RequestSearchDto $dto): JsonResponse
    {
        try {
            $data = $this->getProductSearchAction->handle($paginator, $dto);
            return $this->response($data->toArray(['search']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
