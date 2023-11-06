<?php

namespace App\Dto\Util;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageRequestDto
{
    public function __construct(
        public readonly ?UploadedFile $file = null,
        public ?string $destination = null,
        public ?string $host = null
    )
    {
    }
}