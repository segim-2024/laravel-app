<?php

namespace App\Services;

use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Repositories\Factories\ProductRepositoryFactory;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;

class ProductService implements ProductServiceInterface {
    public function __construct(
        protected ProductRepositoryInterface $repository,
        protected ProductRepositoryFactory $repositoryFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function find(int|string $id): ?ProductInterface
    {
        return $this->repository->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findWithIsWhale(int|string $id, bool $isWhale = false): ?ProductInterface
    {
        return $this->repositoryFactory->createByIsWhale($isWhale)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(MemberInterface $member): Collection
    {
        return $this->repositoryFactory->create($member)->getList();
    }

    public function getSubscribes(ProductInterface $product): Collection
    {
        return $this->repository->getSubscribes($product);
    }
}
