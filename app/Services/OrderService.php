<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface {
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(int|string $id): ?Order
    {
        return $this->repository->find($id);
    }
}
