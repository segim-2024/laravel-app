<?php

namespace App\Http\Resources;

use App\Models\Interfaces\CashInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CashInterface */
class MemberCashResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'member_id' => $this->getMemberId(),
            'amount' => $this->getAmount(),
        ];
    }
}
