<?php

namespace App\DTOs;

use App\Http\Requests\PaymentRetryRequest;
use App\Models\MemberPayment;
use App\Models\MemberSubscribeProduct;

class PaymentRetryDTO
{
    public function __construct(
        public readonly MemberPayment $payment,
        public readonly MemberSubscribeProduct $subscribe
    ) {}

    public static function createFromRequest(PaymentRetryRequest $request): self
    {
        return new self(
            payment: $request->payment,
            subscribe: $request->subscribe
        );
    }
}
