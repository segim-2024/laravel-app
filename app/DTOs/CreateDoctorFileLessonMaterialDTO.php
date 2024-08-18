<?php

namespace App\DTOs;

use App\Http\Requests\CreateDoctorFileLessonMaterialRequest;
use App\Models\DoctorFileLesson;
use Illuminate\Http\UploadedFile;

class CreateDoctorFileLessonMaterialDTO
{
    public function __construct(
        public DoctorFileLesson $lesson,
        public string $title,
        public string $description,
        public string $colorCode,
        public UploadedFile $file
    ) {}

    public static function createFromRequest(CreateDoctorFileLessonMaterialRequest $request): self
    {
        return new self(
            $request->lesson,
            $request->validated('title'),
            $request->validated('description'),
            $request->validated('color_code'),
            $request->validated('file'),
        );
    }
}
