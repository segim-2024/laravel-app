<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorFileNoticeRequest;
use App\Models\DoctorFileNotice;

class UpdateDoctorFileNoticeDTO
{
    public function __construct(
        public readonly DoctorFileNotice $notice,
        public readonly string $title,
        public readonly string $content
    ) {}

    public static function createFromRequest(UpdateDoctorFileNoticeRequest $request): self
    {
        return new self(
            notice: $request->notice,
            title: $request->validated('title'),
            content: $request->validated('content'),
        );
    }
}
