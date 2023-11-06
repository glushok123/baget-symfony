<?php

namespace App\Action\Util;

use App\Dto\Util\UploadImageRequestDto;
use App\Dto\Util\UploadImageResponseDto;
use App\Service\UtilService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class AdminUploadImageAction
{
    public function __construct(
        private UtilService $service
    )
    {
    }

    public function handle(UploadImageRequestDto $dto): UploadImageResponseDto
    {
        return $this->service->uploadImage($dto);
    }
}