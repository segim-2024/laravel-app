<?php

namespace App\DTOs;

use App\Http\Requests\CreateDoctorEssayNoticeRequest;

class CreateDoctorEssayNoticeDTO
{
    public function __construct(
        public string $memberId,
        public bool $isWhale,
        public string $title,
        public string $content,
    ) {}

    // DTO properties and methods can be added here
    public static function createFromRequest(CreateDoctorEssayNoticeRequest $request): self
    {
        return new self(
            memberId: $request->user()->mb_id,
            isWhale: $request->isWhale(),
            title: $request->validated('title'),
            content: $request->validated('content'),
        );
    }

    public function toModelAttributes(): array
    {
        return [
            'member_id' => $this->memberId,
            'is_whale' => $this->isWhale,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
