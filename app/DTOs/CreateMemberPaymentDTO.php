<?php

namespace App\DTOs;

use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use Illuminate\Support\Str;

class CreateMemberPaymentDTO
{
    public function __construct(
        public readonly Member $member,
        public readonly ?MemberCard $card,
        public readonly Product $product,
        public readonly string $paymentId,
        public readonly string $method,
        public readonly string $title,
        public readonly int $amount,
    )
    {
    }

    public static function createFromMemberSubscribe(MemberSubscribeProduct $subscribeProduct):self
    {
        return new self(
            $subscribeProduct->member,
            $subscribeProduct->card,
            $subscribeProduct->product,
            Str::orderedUuid(),
            'card',
            $subscribeProduct->product->name,
            $subscribeProduct->product->price
        );
    }
}
