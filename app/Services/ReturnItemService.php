<?php

namespace App\Services;

use App\Models\ReturnItem;
use App\Repositories\Interfaces\ReturnItemRepositoryInterface;
use App\Services\Interfaces\ReturnItemServiceInterface;

class ReturnItemService implements ReturnItemServiceInterface {
    public function __construct(
        protected ReturnItemRepositoryInterface $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $rfiId): ?ReturnItem
    {
        return $this->repository->find($rfiId);
    }
}
