<?php

namespace App\Http\Resources;

use App\Models\MemberCash;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MemberCash */
class MemberCashResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'member_id' => $this->member_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
