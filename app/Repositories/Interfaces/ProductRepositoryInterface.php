<?php
namespace App\Repositories\Interfaces;

use App\Models\Member;
use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int|string $id): ?Product;

    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;

    /**
     * @param Product $product
     * @return Collection
     */
    public function getSubscribes(Product $product): Collection;
}
