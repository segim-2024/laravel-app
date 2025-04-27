<?php

namespace App\Services\Interfaces;

use App\Models\Cart;

interface CartServiceInterface
{
    /**
     * @param string $cartId
     * @return Cart|null
     */
    public function find(string $cartId): ?Cart;
}
