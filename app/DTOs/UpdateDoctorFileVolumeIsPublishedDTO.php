<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileVolumeIsPublishedRequest;
use App\Models\DoctorFileVolume;

class UpdateDoctorFileVolumeIsPublishedDTO
{
    public function __construct(
        public DoctorFileVolume $volume,
        public bool $isPublished,
    ) {}

    public static function createFromRequest(UpdateDoctorFileVolumeIsPublishedRequest $request): self
    {
        return new self(
            $request->volume,
            $request->validated('is_published'),
        );
    }
}
