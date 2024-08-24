<?php

namespace App\DTOs;

use App\Http\Requests\CreateDoctorFileNoticeRequest;
use App\Models\Member;

class CreateDoctorFileNoticeDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly bool   $isWhale,
        public readonly Member $member,
    ) {}

    public static function createFromRequest(CreateDoctorFileNoticeRequest $request): self
    {
        return new self(
            title: $request->input('title'),
            content: $request->input('content'),
            isWhale: $request->isWhale,
            member: $request->user(),
        );
    }
}
