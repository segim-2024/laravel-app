<?php

namespace App\DTOs;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Http\Requests\LibraryProductUnsubscribeByAdminRequest;
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

    public static function createFromAdminRequest(LibraryProductUnsubscribeByAdminRequest $request): self
    {
        return new self(
            memberId: $request->validated('member_id'),
            productId: (int)$request->validated('product_id'),
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
