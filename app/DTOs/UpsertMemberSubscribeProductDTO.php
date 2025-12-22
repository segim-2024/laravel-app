<?php

namespace App\DTOs;

use App\Http\Requests\UpsertMemberSubscribeProductRequest;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;

class UpsertMemberSubscribeProductDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly ProductInterface $product,
        public readonly CardInterface $card,
    ) {}

    public static function createFromRequest(UpsertMemberSubscribeProductRequest $request): self
    {
        return new self(
            $request->user(),
            $request->product,
            $request->card
        );
    }
}
