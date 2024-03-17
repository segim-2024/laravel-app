<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;

class ProductService implements ProductServiceInterface {
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(int|string $id): ?Product
    {
        return $this->repository->findById($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return $this->repository->getList($member);
    }
}
