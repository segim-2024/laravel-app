<?php

namespace App\DTOs;

use App\Http\Requests\DeleteDoctorEssayNoticeRequest;

class DeleteDoctorEssayNoticeDTO
{
    public function __construct(
        public readonly bool $isWhale,
        public readonly int $noticeId
    ) {}

    public static function createFromRequest(DeleteDoctorEssayNoticeRequest $request):self
    {
        return new self(
            isWhale: $request->isWhale(),
            noticeId: (int)$request->route('noticeId')
        );
    }
}
