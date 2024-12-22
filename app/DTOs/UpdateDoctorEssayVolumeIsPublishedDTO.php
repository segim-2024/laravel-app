<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayVolumeIsPublishedRequest;

class UpdateDoctorEssayVolumeIsPublishedDTO
{
    public function __construct(
        public string $volumeUuid,
        public bool $isPublished,
    ) {}

    public static function createFromRequest(UpdateDoctorEssayVolumeIsPublishedRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('is_published'),
        );
    }
}
