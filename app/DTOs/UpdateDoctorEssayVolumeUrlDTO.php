<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayVolumeUrlRequest;

class UpdateDoctorEssayVolumeUrlDTO
{
    public function __construct(
        public string $volumeUuid,
        public string $url,
    ) {}

    public static function createFromRequest(UpdateDoctorEssayVolumeUrlRequest $request): self
    {
        return new self(
            $request->route('uuid'),
            $request->validated('url'),
        );
    }
}
