<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileVolumeDescriptionRequest;
use App\Models\DoctorFileVolume;

class UpdateDoctorFileVolumeDescriptionDTO
{
    public function __construct(
        public DoctorFileVolume $volume,
        public string $description,
    ) {}

    public static function createFromRequest(UpdateDoctorFileVolumeDescriptionRequest $request): self
    {
        return new self(
            $request->volume,
            $request->validated('description'),
        );
    }
}
