<?php
namespace App\Repositories\Interfaces;

use App\Models\Order;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string|int $id
     * @return Order|null
     */
    public function find(string|int $id): ?Order;
}
