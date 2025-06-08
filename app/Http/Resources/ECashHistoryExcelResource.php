<?php

namespace App\Http\Resources;

use App\Models\MemberCashTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MemberCashTransaction */
class ECashHistoryExcelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'member_id'   => $this->member_id,
            'member_name' => $this->member?->mb_name ?? null,
            'member_nick' => $this->member?->mb_nick ?? null,
            'type'        => $this->type,
            'title'       => $this->title,
            'amount'      => $this->amount,
            'created_at'  => $this->created_at,
        ];
    }
}
