<?php

namespace App\Services\Interfaces;

use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    /**
     * @param string|int $id
     * @return Product|null
     */
    public function find(string|int $id):?Product;

    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;

    /**
     * 상품의 구독 리스트 구하기
     *
     * @param Product $product
     * @return Collection
     */
    public function getSubscribes(Product $product): Collection;
}
