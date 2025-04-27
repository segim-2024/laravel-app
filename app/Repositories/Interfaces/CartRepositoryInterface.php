<?php
namespace App\Repositories\Interfaces;

use App\Models\Cart;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $cartId
     * @return Cart|null
     */
    public function find(string $cartId): ?Cart;
}
