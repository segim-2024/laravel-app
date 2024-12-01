<?php

namespace App\DTOs;

use App\Http\Requests\UpdateLibraryProductSubscribeCardRequest;

class UpdateLibraryProductSubscribeCardDTO
{
    public function __construct(
        public readonly string $memberId,
        public readonly int $productId,
        public readonly int $cardId
    ) {}

    public static function createFromRequest(UpdateLibraryProductSubscribeCardRequest $request): self
    {
        return new self(
            memberId: $request->user()->mb_id,
            productId: (int)$request->route('productId'),
            cardId: (int)$request->validated('card_id')
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'card_id' => $this->cardId
        ];
    }
}
