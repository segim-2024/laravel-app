<?php
namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string|int $id): ?Order
    {
        return Order::where('od_id', '=', $id)->first();
    }
}
