<?php

namespace App\DTOs;

use App\Http\Requests\AlimTokSendRequest;
use App\Http\Requests\ShipmentTrackAlimTokSendRequest;
use App\Models\Order;

class OrderDTO
{
    public function __construct(
        public Order $order,
    ) {}

    public static function createFromAlimTokRequest(AlimTokSendRequest $request):self
    {
        return new self(
            order: $request->order,
        );
    }

    public static function createFromTrackRequest(ShipmentTrackAlimTokSendRequest $request):self
    {
        return new self(
            order: $request->order,
        );
    }
}
