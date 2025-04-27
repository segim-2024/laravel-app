<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Services\Interfaces\CartServiceInterface;

class CartService implements CartServiceInterface {
    public function __construct(
        protected CartRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $cartId): ?Cart
    {
        return $this->repository->find($cartId);
    }
}
