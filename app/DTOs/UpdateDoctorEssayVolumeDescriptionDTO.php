<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayVolumeDescriptionRequest;

class UpdateDoctorEssayVolumeDescriptionDTO
{
    public function __construct(
        public string $volumeUuid,
        public string $description,
    ) {}

    public static function createFromRequest(UpdateDoctorEssayVolumeDescriptionRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('description'),
        );
    }
}
