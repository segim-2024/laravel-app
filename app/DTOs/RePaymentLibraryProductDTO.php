<?php

namespace App\DTOs;

use App\Http\Requests\RePaymentLibraryProductRequest;

class RePaymentLibraryProductDTO
{
    public function __construct(
        public string $memberId,
        public int $productId,
    ) {}

    // DTO properties and methods can be added here
    public static function createFromRequest(RePaymentLibraryProductRequest $request):self
    {
        return new self(
            $request->user()->mb_id,
            (int)$request->route('productId'),
        );
    }
}
