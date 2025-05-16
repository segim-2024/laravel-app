<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileVolumeUrlRequest;

class UpdateDoctorFileVolumeUrlDTO
{
    public function __construct(
        public string $volumeUuid,
        public string $url,
    ) {}

    public static function createFromRequest(UpdateDoctorFileVolumeUrlRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('url'),
        );
    }
}
