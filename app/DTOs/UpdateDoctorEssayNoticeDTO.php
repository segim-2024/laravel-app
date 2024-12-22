<?php

namespace App\DTOs;

use App\Http\Requests\UpdateDoctorEssayNoticeRequest;

class UpdateDoctorEssayNoticeDTO
{
    public function __construct(
        public readonly bool $isWhale,
        public readonly int $noticeId,
        public readonly string $title,
        public readonly string $content
    ) {}

    public static function createFromRequest(UpdateDoctorEssayNoticeRequest $request): self
    {
        return new self(
            isWhale: $request->isWhale(),
            noticeId: (int)$request->route('noticeId'),
            title: $request->validated('title'),
            content: $request->validated('content'),
        );
    }

    public function toModelAttributes(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
