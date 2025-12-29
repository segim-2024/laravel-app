<?php

namespace App\Services\Interfaces;

use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    /**
     * @param string|int $id
     * @return ProductInterface|null
     */
    public function find(string|int $id): ?ProductInterface;

    /**
     * isWhale 플래그로 상품 조회
     *
     * @param string|int $id
     * @param bool $isWhale
     * @return ProductInterface|null
     */
    public function findWithIsWhale(string|int $id, bool $isWhale = false): ?ProductInterface;

    /**
     * @param MemberInterface $member
     * @return Collection
     */
    public function getList(MemberInterface $member): Collection;

    /**
     * 상품의 구독 리스트 구하기
     *
     * @param ProductInterface $product
     * @return Collection
     */
    public function getSubscribes(ProductInterface $product): Collection;
}
