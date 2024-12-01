<?php

namespace App\DTOs;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Models\MemberPayment;

class UpdateLibraryProductSubscribeStateToUnpaidDTO
{
    public function __construct(
        public readonly string $memberId,
        public readonly int $productId,
    ) {}

    public static function createFromPayment(MemberPayment $payment): self
    {
        return new self(
            $payment->member_id,
            $payment->productable->id,
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'state' => LibraryProductSubscribeStateEnum::Unpaid,
            'due_date' => null,
        ];
    }
}
