<?php

namespace App\Services\Interfaces;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use Illuminate\Support\Collection;

interface MemberSubscribeProductServiceInterface
{
    /**
     * @param Member $member
     * @param Product $product
     * @return MemberSubscribeProduct|null
     */
    public function findByMemberAndProduct(Member $member, Product $product): ?MemberSubscribeProduct;

    /**
     * @param UpsertMemberSubscribeProductDTO $DTO
     * @return Collection
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO):Collection;

    /**
     * @param MemberSubscribeProduct $subscribeProduct
     * @return MemberSubscribeProduct
     */
    public function updateLatestPayment(MemberSubscribeProduct $subscribeProduct): MemberSubscribeProduct;
}
