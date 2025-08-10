<?php
namespace App\Repositories\Interfaces;

use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;

interface MemberSubscribeProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Member $member
     * @param Product $product
     * @return MemberSubscribeProduct|null
     */
    public function findByMemberAndProduct(Member $member, Product $product): ?MemberSubscribeProduct;

    /**
     * @param UpsertMemberSubscribeProductDTO $DTO
     * @return MemberSubscribeProduct
     */
    public function upsertCard(UpsertMemberSubscribeProductDTO $DTO):MemberSubscribeProduct;

    /**
     * @param MemberSubscribeProduct $subscribeProduct
     * @return MemberSubscribeProduct
     */
    public function updateLatestPayment(MemberSubscribeProduct $subscribeProduct): MemberSubscribeProduct;

    /**
     * @param UpdateActivateMemberSubscribeProductDTO $DTO
     * @return MemberSubscribeProduct
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): MemberSubscribeProduct;

    /**
     * @param Member $member
     * @return bool
     */
    public function isExistsSubscribe(Member $member): bool;
}
