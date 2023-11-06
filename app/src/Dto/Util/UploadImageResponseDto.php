<?php

namespace App\Dto\Util;

use App\Dto\BasicDto;

class UploadImageResponseDto extends BasicDto
{
    public function __construct(
        public readonly string $url,
        public readonly bool   $uploaded,
        public readonly string $fileName
    )
    {
    }
}