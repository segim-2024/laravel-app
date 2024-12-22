<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayVolumePosterRequest;
use Illuminate\Http\UploadedFile;

class UpdateDoctorEssayVolumePosterDTO
{
    public function __construct(
        public string $volumeUuid,
        public UploadedFile $poster,
    ) {}


    public static function createFromRequest(UpdateDoctorEssayVolumePosterRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('poster'),
        );
    }
}
