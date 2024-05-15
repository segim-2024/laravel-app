<?php
namespace App\Repositories\Interfaces;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberSubscribeProduct;

interface MemberSubscribeProductRepositoryInterface extends BaseRepositoryInterface
{
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
}
