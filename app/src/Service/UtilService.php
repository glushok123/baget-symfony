<?php

namespace App\Service;

use App\Dto\Util\UploadImageRequestDto;
use App\Dto\Util\UploadImageResponseDto;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class UtilService
{
    const ARTICLE_IMAGE_UPLOAD_PATH = '/article';
    const MOVE_PATH = '/app/public/upload';

    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function uploadImage(UploadImageRequestDto $dto): UploadImageResponseDto
    {
        if (empty($dto->destination)
            || empty($dto->file)
            || empty($dto->host)
        ) {
            throw new Exception($this->translator->trans('image_could_not_be_uploaded'));
        }
        $file = $dto->file;
        $movePath = self::MOVE_PATH;
        $hostPath = $dto->host . '/upload';
        $name = 'article-' . date('Y-m-d_H-i-s') . '.' . $file->getClientOriginalExtension();
        switch ($dto->destination) {
            case 'article':
                $movePath = $movePath . self::ARTICLE_IMAGE_UPLOAD_PATH;
                $hostPath = $hostPath . self::ARTICLE_IMAGE_UPLOAD_PATH;
                break;
        }
        $file->move($movePath, $name);

        return new UploadImageResponseDto(
            url: $hostPath . '/' . $name, uploaded: 1, fileName: $name
        );
    }
}