<?php

namespace App\Http\Resources;

use App\Models\LibraryProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LibraryProduct */
class LibraryProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'price'              => $this->price,
            'ticket_provide_qty' => $this->ticket_provide_qty,
            'is_hided'           => $this->is_hided,
            'deleted_at'         => $this->deleted_at,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'subscribe'          => new LibraryProductSubscribeResource($this->whenLoaded('subscribe')),
        ];
    }
}
