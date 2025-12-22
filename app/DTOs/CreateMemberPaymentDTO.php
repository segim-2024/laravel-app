<?php

namespace App\DTOs;

use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\LibraryProduct;
use App\Models\LibraryProductSubscribe;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Models\WhaleProduct;
use Illuminate\Support\Str;

class CreateMemberPaymentDTO
{
    public function __construct(
        public readonly MemberInterface $member,
        public readonly ?CardInterface $card,
        public readonly Product|LibraryProduct|WhaleProduct $product,
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

    public static function createFromLibrarySubscribe(LibraryProductSubscribe $subscribe):self
    {
        return new self(
            $subscribe->member,
            $subscribe->card,
            $subscribe->product,
            Str::orderedUuid(),
            'card',
            $subscribe->product->name,
            $subscribe->product->price
        );
    }
}
