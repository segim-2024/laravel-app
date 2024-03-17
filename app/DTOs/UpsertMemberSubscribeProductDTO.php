<?php

namespace App\DTOs;

use App\Http\Requests\UpsertMemberSubscribeProductRequest;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\Product;

class UpsertMemberSubscribeProductDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly Product $product,
        public readonly MemberCard $card,
    ) {}

    public static function createFromRequest(UpsertMemberSubscribeProductRequest $request):self
    {
        return new self(
            $request->user(),
            $request->product,
            $request->card
        );
    }
}
