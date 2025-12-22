<?php

namespace App\DTOs;

use App\Http\Requests\UpdateActivateMemberSubscribeProductRequest;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;

class UpdateActivateMemberSubscribeProductDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly ProductInterface $product,
        public readonly SubscribeProductInterface $subscribe,
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
