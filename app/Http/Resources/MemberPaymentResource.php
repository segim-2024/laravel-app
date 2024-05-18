<?php

namespace App\Http\Resources;

use App\Models\MemberPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MemberPayment */
class MemberPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'card_id' => $this->card_id,
            'payment_id' => $this->payment_id,
            'state' => $this->state,
            'method' => $this->method,
            'title' => $this->title,
            'amount' => $this->amount,
            'cancelled_amount' => $this->cancelled_amount,
            'reason' => $this->reason,
            'receipt_url' => $this->receipt_url,
            'paid_at' => $this->paid_at,
            'cancelled_at' => $this->cancelled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
