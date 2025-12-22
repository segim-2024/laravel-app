<?php

namespace App\DTOs;

use App\Http\Requests\PaymentCancelRequest;
use App\Models\Interfaces\PaymentInterface;

class PaymentCancelDTO
{
    public function __construct(
        public readonly PaymentInterface $payment,
        public readonly int $amount,
        public readonly ?string $reason,
    ) {}

    public static function createFromRequest(PaymentCancelRequest $request): self
    {
        return new self(
            payment: $request->payment,
            amount: $request->validated('amount'),
            reason: $request->validated('reason'),
        );
    }
}
