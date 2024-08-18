<?php

namespace App\DTOs;

use App\Models\DoctorFileLesson;
use App\Models\DoctorFileVolume;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateFileDTO
{
    public function __construct(
        public string $uuid,
        public string $path,
        public UploadedFile $file
    ) {}

    public static function createFromDoctorFileVolume(DoctorFileVolume $volume, UploadedFile $file): self
    {
        return new self(
            Str::orderedUuid(),
            "doctor-file/volumes/{$volume->volume_uuid}/poster",
            $file
        );
    }

    public static function createFromDoctorFileLessonMaterial(DoctorFileLesson $lesson, UploadedFile $file): self
    {
        return new self(
            Str::orderedUuid(),
            "doctor-file/lessons/{$lesson->lesson_uuid}/materials",
            $file
        );
    }
}
