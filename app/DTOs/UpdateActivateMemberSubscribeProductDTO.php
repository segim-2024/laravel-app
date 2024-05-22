<?php

namespace App\DTOs;

use App\Http\Requests\UpdateActivateMemberSubscribeProductRequest;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;

class UpdateActivateMemberSubscribeProductDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly Product $product,
        public readonly MemberSubscribeProduct $subscribe,
        public readonly bool $isActive
    ) {}

    public static function createFromRequest(UpdateActivateMemberSubscribeProductRequest $request): self
    {
        return new self(
            member: $request->member,
            product: $request->product,
            subscribe: $request->subscribe,
            isActive: $request->validated('is_active')
        );
    }
}
