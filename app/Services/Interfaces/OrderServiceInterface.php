<?php

namespace App\Services\Interfaces;

use App\Models\Order;

interface OrderServiceInterface
{
    /**
     * @param string|int $id
     * @return Order|null
     */
    public function find(string|int $id): ?Order;
}
