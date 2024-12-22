<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayMaterialRequest;
use Illuminate\Http\UploadedFile;

class UpdateDoctorEssayMaterialDTO
{
    public function __construct(
        public string $materialUuid,
        public string $title,
        public string $colorCode,
        public string $bgColorCode,
        public ?UploadedFile $file = null
    ) {}

    public static function createFromRequest(UpdateDoctorEssayMaterialRequest $request): self
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
