<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileLessonMaterialRequest;
use App\Models\DoctorFileLessonMaterial;
use Illuminate\Http\UploadedFile;

class UpdateDoctorFileLessonMaterialDTO
{
    public function __construct(
        public DoctorFileLessonMaterial $material,
        public string $title,
        public string $description,
        public string $colorCode,
        public ?UploadedFile $file = null
    ) {}

    public static function createFromRequest(UpdateDoctorFileLessonMaterialRequest $request): self
    {
        return new self(
            $request->material,
            $request->validated('title'),
            $request->validated('description'),
            $request->validated('color_code'),
            $request->validated('file'),
        );
    }
}
