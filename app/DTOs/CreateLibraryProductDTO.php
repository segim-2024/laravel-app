<?php

namespace App\DTOs;

use App\Http\Requests\CreateLibraryProductRequest;

class CreateLibraryProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $price,
        public readonly int $ticketProvideQty,
        public readonly bool $isHided,
    ) {}

    public static function createFromRequest(CreateLibraryProductRequest $request):self
    {
        return new self(
            name: $request->validated('name'),
            price: $request->validated('price'),
            ticketProvideQty: $request->validated('ticket_provide_qty'),
            isHided: $request->validated('is_hided')
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
