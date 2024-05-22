<?php

namespace App\DTOs;

use App\Http\Requests\UnsubscribeProductRequest;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;

class UnsubscribeProductDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly Product $product,
        public readonly MemberSubscribeProduct $subscribe,
    ) {}

    // DTO properties and methods can be added here
    public static function createFromRequest(UnsubscribeProductRequest $request): self
    {
        return new self(
            member: $request->member,
            product: $request->product,
            subscribe: $request->subscribe,
        );
    }
}
