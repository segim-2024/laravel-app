<?php

namespace App\DTOs;

use App\Models\MemberPayment;

class UpdateLibraryProductSubscribeDatesDTO
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
}
