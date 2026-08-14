<?php

namespace App\DTOs;

use App\Enums\LectureFileTypeEnum;

class DeleteLectureFileDTO
{
    public function __construct(
        public LectureFileTypeEnum $type,
        public string $fileName
    ) {}
}
