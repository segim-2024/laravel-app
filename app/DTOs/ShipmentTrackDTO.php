<?php

namespace App\DTOs;

use App\Http\Requests\ShipmentTrackAlimTokSendRequest;
use App\Models\Order;

class ShipmentTrackDTO
{
    public function __construct(
        public Order $order,
    ) {}

    public static function createFromRequest(ShipmentTrackAlimTokSendRequest $request):self
    {
        return new self(
            order: $request->order,
        );
    }
}
