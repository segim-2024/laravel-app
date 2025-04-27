<?php
namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $cartId): ?Cart
    {
        return Cart::where('ct_id', $cartId)
            ->with(['item', 'member'])
            ->first();
    }
}
