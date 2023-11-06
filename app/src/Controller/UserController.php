<?php

namespace App\Controller;

use App\Action\Auth\RecoveryPasswordUserAction;
use App\Action\User\GetUserOrganizationAction;
use App\Action\User\UserChangePasswordAction;
use App\Action\User\UserCreateAction;
use App\Action\User\UserDeleteAction;
use App\Action\User\UserDeleteEmployeeAction;
use App\Action\User\UserGetAction;
use App\Action\User\UserGetByTokenAction;
use App\Action\User\UserGetCollectionAction;
use App\Action\User\UserGetCollectionEmployeeAction;
use App\Action\User\UserInvitationAction;
use App\Action\User\UserRecoveryPasswordAction;
use App\Action\User\UserUpdateAction;
use App\Action\User\UserUpdateEmployeeAction;
use App\Dto\Auth\UserRecoveryPasswordDto;
use App\Dto\Organization\OrganizationDto;
use App\Dto\Organization\OrganizationFilterDto;
use App\Dto\User\UserChangePasswordDto;
use App\Dto\User\UserDto;
use App\Entity\User\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class UserController extends ApiController
{
    public function __construct(
        private readonly UserCreateAction                           $createAction,
        private readonly UserInvitationAction                       $invitationAction,
        private readonly UserUpdateAction                           $updateAction,
        private readonly UserGetCollectionAction                    $getCollectionAction,
        private readonly UserGetAction                              $getUserAction,
        private readonly UserDeleteAction                           $deleteAction,
        private readonly UserRecoveryPasswordAction                 $sendUrlForRecoveryPasswordAction,
        private readonly UserChangePasswordAction                   $changePasswordAction,
        private readonly RecoveryPasswordUserAction                 $recoveryPasswordAction,
        private readonly GetUserOrganizationAction                  $getOrganizationUserAction,
        private readonly UserGetByTokenAction                       $userGetByTokenAction,
        private readonly UserGetCollectionEmployeeAction            $getCollectionEmployeeAction,
        private readonly UserUpdateEmployeeAction                   $updateEmployeeAction,
        private readonly UserDeleteEmployeeAction                   $deleteEmployeeAction,
        private readonly ValidatorInterface                         $validatorInterface
    )
    {
        parent::__construct($this->validatorInterface); //todo: придумать по лучше
    }

    #[IsGranted('ROLE_USER_EDIT')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['create_user', 'organization_id', 'price_name', 'manager_id', 'password_user'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Создание пользователя',
        content: new Model(type: UserDto::class),
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/create', name: 'app_user_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserDto $dto): JsonResponse
    {
        try {
            $this->validation($dto, ['create_user']);

            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_EDIT')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['create_user', 'organization_id', 'price_name', 'manager_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление пользователя (можно отправлять id и измененный атрибут)',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/update', name: 'app_user_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] UserDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $this->validation($dto, ['update_user']);

            $data = $this->updateAction->handle($dto, $user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_DELETE')]
    #[OA\Response(
        response: 200,
        description: 'Удаление пользователя',
        content: new Model(type: UserDto::class, groups: ['user_id'])
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/delete', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(#[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->deleteAction->handle($user);
            return $this->response($data->toArray(['user_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\Response(
        response: 200,
        description: 'Получение списка пользователей',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: UserDto::class)))
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/get-collection', name: 'app_user_get_collection', methods: ['GET'])]
    public function getCollection(): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle();
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение пользователя по ID, EMAIL или hash',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/get', name: 'app_user_get', methods: ['GET'])]
    public function getUserByAttribute(#[MapQueryString] UserDto $dto): JsonResponse
    {
       try {
            $data = $this->getUserAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_ORGANIZATION_READ')]
    #[OA\Parameter(
        parameter: "withoutEmployee",
        in:"query",
        name:"withoutEmployee",
        description:"Без сотрудников",
        example: 'withoutEmployee=true',
        schema: new OA\Schema(type: "boolean")
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение всех организаций пользователя',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationDto::class)))
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/get/organization', name: 'app_user_get_organization', methods: ['GET'])]
    public function getUserAllOrganization(#[MapQueryString] ?OrganizationFilterDto $organizationFilterDto, #[CurrentUser] ?User $user): JsonResponse
    {
       try {
            $data = $this->getOrganizationUserAction->handle($user, $organizationFilterDto);
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\Response(
        response: 200,
        description: 'Получение пользователя по токену в заголовке',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/get/token', name: 'app_user_get_by_token', methods: ['GET'])]
    public function getUserByToken(#[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->userGetByTokenAction->handle($user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_EDIT')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['invitation_user', 'organization_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Приглашение пользователя (сотрудника)',
        content: new Model(type: UserDto::class, groups: ['user_id'])
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/invitation', name: 'app_user_invitation', methods: ['POST'])]
    public function invitationUser(#[MapRequestPayload] UserDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $this->validation($dto, ['invitation_user']);

            $data = $this->invitationAction->handle($dto, $user);
            return $this->response($data->toArray(['user_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Security]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['user_email'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Восстановление пароля (генерация хеша и отправка на почту ссылки)',
        content: new Model(type: UserDto::class, groups: ['user_id'])
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/password/recovery', name: 'app_user_send_hash_for_password_recovery', methods: ['POST'])]
    public function sendUrlForRecoveryPasswordUser(#[MapRequestPayload] UserDto $dto): JsonResponse
    {
        try {
            $data = $this->sendUrlForRecoveryPasswordAction->handle($dto);
            return $this->response($data->toArray(['user_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserRecoveryPasswordDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Восстановление пароля',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/password/recovery', name: 'app_user_password_recovery', methods: ['PUT'])]
    public function recoveryPasswordUser(#[MapRequestPayload] UserRecoveryPasswordDto $dto): JsonResponse
    {
        try {
            $data = $this->recoveryPasswordAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_EDIT')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserChangePasswordDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Смена пароля в ЛК',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/password/change', name: 'app_user_password_change', methods: ['PUT'])]
    public function changePasswordUser(#[MapRequestPayload] UserChangePasswordDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->changePasswordAction->handle($dto, $user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\Response(
        response: 200,
        description: 'Получение списка сотрудников пользователя',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: UserDto::class)))
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/get-collection/employee', name: 'app_user_get_collection_employee', methods: ['GET'])]
    public function getCollectionEmployee(#[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->getCollectionEmployeeAction->handle($user);
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['create_user', 'organization_id', 'price_name', 'user_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление сотрудника',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/update/employee', name: 'app_user_update_employee', methods: ['PUT'])]
    public function updateEmployee(#[MapRequestPayload] UserDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $this->validation($dto, ['update_user']);

            $data = $this->updateEmployeeAction->handle($dto, $user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_USER_READ')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserDto::class, groups: ['user_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Удаление сотрудника',
        content: new Model(type: UserDto::class, groups: ['user_id'])
    )]
    #[OA\Tag(name: 'User')]
    #[Route('api/user/delete/employee', name: 'app_user_delete_employee', methods: ['DELETE'])]
    public function deleteEmployee(#[MapRequestPayload] UserDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->deleteEmployeeAction->handle($dto, $user);
            return $this->response($data->toArray(['user_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
