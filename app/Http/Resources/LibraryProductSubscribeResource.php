<?php

namespace App\Http\Resources;

use App\Models\LibraryProductSubscribe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LibraryProductSubscribe */
class LibraryProductSubscribeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'state'       => $this->state,
            'start'       => $this->start,
            'end'         => $this->end,
            'due_date'    => $this->due_date,
            'payment_day' => $this->payment_day,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'product'     => new LibraryProductResource($this->whenLoaded('product')),
            'card'        => new MemberCardResource($this->whenLoaded('card')),
        ];
    }
}
