<?php

namespace App\DTOs;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Http\Requests\LibraryProductSubscribeRequest;

class LibraryProductSubscribeDTO
{
    public function __construct(
        public string $memberId,
        public int $libraryProductId,
        public int $cardId,
        public string $paymentDay,
    ) {}

    public static function createFromRequest(LibraryProductSubscribeRequest $request):self
    {
        return new self(
            memberId: $request->user()->mb_id,
            libraryProductId: (int)$request->route('productId'),
            cardId: $request->validated('card_id'),
            paymentDay: $request->validated('payment_day'),
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'member_id' => $this->memberId,
            'product_id' => $this->libraryProductId,
            'card_id' => $this->cardId,
            'state' => LibraryProductSubscribeStateEnum::Subscribe,
            'start' => null,
            'end' => null,
            'payment_day' => $this->paymentDay,
        ];
    }
}
