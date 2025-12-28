<?php
namespace App\Repositories\Interfaces;

use App\Models\Interfaces\ProductInterface;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int|string $id): ?ProductInterface;

    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param ProductInterface $product
     * @return Collection
     */
    public function getSubscribes(ProductInterface $product): Collection;
}
