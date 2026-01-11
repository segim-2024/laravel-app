<?php

namespace App\DTOs;

use App\Http\Requests\ForceStartSubscribeRequest;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;

class ForceStartSubscribeDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly ProductInterface $product,
        public readonly SubscribeProductInterface $subscribe,
    ) {}

    public static function createFromRequest(ForceStartSubscribeRequest $request): self
    {
        return new self(
            member: $request->member,
            product: $request->product,
            subscribe: $request->subscribe,
        );
    }
}
