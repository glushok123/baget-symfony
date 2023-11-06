<?php

namespace App\Controller;

use App\Action\Appeal\AppealCreateAction;
use App\Action\Appeal\AppealGetCollectionAction;
use App\Dto\Appeal\AppealDto;
use App\Dto\Appeal\Filter\FilterDto;
use App\Dto\Product\Request\RequestSearchDto;
use App\Entity\User\User;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Throwable;

class AppealController extends ApiController
{
    public function __construct(
        private readonly AppealCreateAction             $createAction,
        private readonly AppealGetCollectionAction      $getCollectionAction,
        private readonly ValidatorInterface             $validatorInterface,
    )
    {
        parent::__construct($this->validatorInterface);
    }

    #[IsGranted('ROLE_USER')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: AppealDto::class, groups: ['create_appeal'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Создание обращения/претензии',
        content: new Model(type: AppealDto::class, groups: ['appeal'])
    )]
    #[OA\Tag(name: 'Appeal')]
    #[Route('api/appeal/create', name: 'app_appeal_get', methods: ['POST'])]
    public function create(#[MapRequestPayload] AppealDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $this->validation($dto, ['create_appeal']);

            $data = $this->createAction->handle($dto, $user);
            return $this->response($data->toArray(['appeal']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER')]
    #[OA\Parameter(
        parameter: "query",
        in:"query",
        name:"query",
        description:"Поиск по номеру",
        example: 'query=123456',
        schema: new OA\Schema(type: "int"),
        required: true
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
        description:"Кол. на странице",
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
        parameter: "status",
        in:"query",
        name:"status",
        description:"Тип",
        example: 'status=1',
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение списка обращений пользователя',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: AppealDto::class, groups: ['appeal'])))
    )]
    #[OA\Tag(name: 'Appeal')]
    #[Route('api/appeal/get-collection', name: 'app_appeal_get_collection', methods: ['GET'])]
    public function getCollection(PaginatorInterface $paginator, #[MapQueryString] FilterDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle($paginator, $dto, $user);
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
