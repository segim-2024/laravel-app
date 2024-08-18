<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileVolumePosterRequest;
use App\Models\DoctorFileVolume;
use Illuminate\Http\UploadedFile;

class UpdateDoctorFileVolumePosterDTO
{
    public function __construct(
        public DoctorFileVolume $volume,
        public UploadedFile $poster,
    ) {}

    public static function createFromRequest(UpdateDoctorFileVolumePosterRequest $request): self
    {
        return new self(
            $request->volume,
            $request->validated('poster'),
        );
    }
}
