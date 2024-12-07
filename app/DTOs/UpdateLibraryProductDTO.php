<?php

namespace App\DTOs;

use App\Http\Requests\UpdateLibraryProductRequest;

class UpdateLibraryProductDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $price,
        public int $ticketProvideQty,
        public bool $isHided,
    ) {}

    public static function createFromRequest(UpdateLibraryProductRequest $request):self
    {
        return new self(
            id: (int)$request->route('productId'),
            name: $request->validated('name'),
            price: $request->validated('price'),
            ticketProvideQty: $request->validated('ticket_provide_qty'),
            isHided: $request->validated('is_hided'),
        );
    }

    public function toModelAttribute(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'ticket_provide_qty' => $this->ticketProvideQty,
            'is_hided' => $this->isHided,
        ];
    }
}
