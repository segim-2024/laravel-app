<?php

namespace App\DTOs;

use App\Enums\S3FileTypeEnum;

class DeleteS3FileDTO
{
    public function __construct(
        public S3FileTypeEnum $type,
        public string $fileName
    ) {}
}
