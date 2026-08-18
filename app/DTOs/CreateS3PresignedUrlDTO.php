<?php

namespace App\DTOs;

use App\Enums\S3FileTypeEnum;

class CreateS3PresignedUrlDTO
{
    public function __construct(
        public S3FileTypeEnum $type,
        public string $extension
    ) {}
}
