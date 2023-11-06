<?php


namespace App\Controller;


use App\Action\Auth\CheckCodeAction;
use App\Action\Auth\LoginUserAction;
use App\Action\Auth\ReceiveCodeAction;
use App\Action\Auth\RegisterFinalAction;
use App\Dto\Auth\CheckCodeRequestDto;
use App\Dto\Auth\CheckCodeResponseDto;
use App\Dto\Auth\LoginUserRequestDto;
use App\Dto\Auth\ReceiveCodeRequestDto;
use App\Dto\Auth\ReceiveCodeResponseDto;
use App\Dto\Auth\RegisterRequestDto;
use App\Dto\Auth\RegisterResponseDto;
use Nelmio\ApiDocBundle\Annotation\Areas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[AsController]
#[Areas(['Auth'])]
class AuthController extends ApiController
{
    public function __construct(
        private readonly RegisterFinalAction  $registerFinalRequestAction,
        private readonly LoginUserAction      $loginUserRequestAction,
        private readonly ReceiveCodeAction    $receiveCodeAction,
        private readonly CheckCodeAction      $checkCodeAction
    )
    {
    }

    #[Security]
    #[OA\RequestBody(
        content: new Model(type: RegisterRequestDto::class, groups: ['registration'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Регистрация',
        content: new Model(type: RegisterResponseDto::class)
    )]
    #[OA\Tag(name: 'Auth')]
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function registerFinal(#[MapRequestPayload] RegisterRequestDto $dto): JsonResponse
    {
        try {
            $data = $this->registerFinalRequestAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Security]
    #[Route('/api/login', name: 'api_login')]
    #[OA\Tag(name: 'Auth')]
    public function login(#[MapRequestPayload] LoginUserRequestDto $dto): JsonResponse
    {
        try {
            $data = $this->loginUserRequestAction->handle($dto);

            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER')]
    #[OA\Parameter(
        parameter: "email",
        in:"query",
        name:"email",
        description:"Отправка кода подтверждения email",
        example: 'email=admin@admin.ru',
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: 'Отправка кода подтверждения email',
        content: new Model(type: ReceiveCodeResponseDto::class)
    )]
    #[OA\Tag(name: 'Auth')]
    #[Route('/api/register/code-receive', name: 'api_code_receive', methods: ['GET'])]
    public function receiveCode(#[MapQueryString] ReceiveCodeRequestDto $dto): JsonResponse
    {
        try {
            $data = $this->receiveCodeAction->handle($dto);

            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER')]
    #[OA\RequestBody(
        content: new Model(type: CheckCodeRequestDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Проверка кода подтверждения email',
        content: new Model(type: CheckCodeResponseDto::class)
    )]
    #[OA\Tag(name: 'Auth')]
    #[Route('/api/register/code-check', name: 'api_code_check', methods: ['POST'])]
    public function checkCode(#[MapRequestPayload] CheckCodeRequestDto $dto): JsonResponse
    {
        try {
            $data = $this->checkCodeAction->handle($dto);

            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }


}
