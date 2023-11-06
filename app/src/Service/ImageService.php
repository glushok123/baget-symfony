<?php

namespace App\Service;

use App\Dto\Product\Image\ImageDto;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ImageService // todo: надо реализовать ресайз и сохранение картинок в разных разрешениях
{
    const PATH_IMAGE_PRODUCT = '/upload/images/product/';

    public function __construct(
        private readonly CacheManager $imagineCacheManager
    )
    {
    }

    public function getResizeImagePath(string $filename): ImageDto
    {
        return new ImageDto(
            resolvedPathBig: $this->imagineCacheManager->getBrowserPath(self::PATH_IMAGE_PRODUCT . $filename, 'big'),
            resolvedPathSmall: $this->imagineCacheManager->getBrowserPath(self::PATH_IMAGE_PRODUCT . $filename, 'small'),
            resolvedPathMedium: $this->imagineCacheManager->getBrowserPath(self::PATH_IMAGE_PRODUCT . $filename, 'medium'),
        );
    }
}
