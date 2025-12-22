<?php

namespace App\DTOs;

use App\Http\Requests\PaymentRetryRequest;
use App\Models\Interfaces\PaymentInterface;
use App\Models\Interfaces\SubscribeProductInterface;

class PaymentRetryDTO
{
    public function __construct(
        public PaymentInterface $payment,
        public readonly SubscribeProductInterface $subscribe
    ) {}

    public static function createFromRequest(PaymentRetryRequest $request): self
    {
        return new self(
            payment: $request->payment,
            subscribe: $request->subscribe
        );
    }
}
