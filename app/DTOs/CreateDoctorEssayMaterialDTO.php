<?php

namespace App\DTOs;

use App\Http\Requests\CreateDoctorEssayMaterialRequest;
use Illuminate\Http\UploadedFile;

class CreateDoctorEssayMaterialDTO
{
    public function __construct(
        public string       $lessonUuid,
        public string       $title,
        public ?string      $colorCode,
        public ?string      $bgColorCode,
        public UploadedFile $file,
    ) {}

    public static function createFromRequest(CreateDoctorEssayMaterialRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('title'),
            $request->validated('color_code'),
            $request->validated('bg_color_code'),
            $request->validated('file'),
        );
    }
}
