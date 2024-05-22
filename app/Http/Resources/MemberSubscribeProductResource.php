<?php

namespace App\Http\Resources;

use App\Models\MemberSubscribeProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MemberSubscribeProduct */
class MemberSubscribeProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'product_id' => $this->product_id,
            'card_id' => $this->card_id,
            'latest_payment_at' => $this->latest_payment_at,
            'is_started' => $this->is_started,
            'is_activated' => $this->is_activated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'card' => new MemberCardResource($this->card),
        ];
    }
}
