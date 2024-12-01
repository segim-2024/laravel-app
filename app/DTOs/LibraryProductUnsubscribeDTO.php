<?php

namespace App\DTOs;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Http\Requests\LibraryProductUnsubscribeRequest;

class LibraryProductUnsubscribeDTO
{
    public function __construct(
        public readonly string $memberId,
        public readonly int $productId,
    ) {}

    public static function createFromRequest(LibraryProductUnsubscribeRequest $request): self
    {
        return new self(
            memberId: $request->user()->mb_id,
            productId: (int)$request->route('productId'),
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'state' => LibraryProductSubscribeStateEnum::Unsubscribe,
            'due_date' => null,
        ];
    }
}
