<?php

namespace App\Controller;

use App\Action\Util\AdminUploadImageAction;
use App\Dto\Util\UploadImageRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class UtilController extends ApiController
{
    public function __construct(
        private readonly AdminUploadImageAction $adminUploadImageAction
    )
    {
    }

    #[Route('/upload-image', name: 'admin_image_upload')]
    public function index(Request $request): Response
    {
        try {
            $dto = new UploadImageRequestDto(
                file: $request->files->get('upload'),
                destination: 'article',
                host: $request->getSchemeAndHttpHost()
            );
            $data = $this->adminUploadImageAction->handle($dto);
            return new JsonResponse(
                $data->toArray()
            );
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
