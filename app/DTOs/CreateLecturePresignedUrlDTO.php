<?php

namespace App\DTOs;

use App\Enums\LectureFileTypeEnum;

class CreateLecturePresignedUrlDTO
{
    public function __construct(
        public LectureFileTypeEnum $type,
        public string $extension
    ) {}
}
