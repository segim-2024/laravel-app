<?php
namespace App\Repositories\Interfaces;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberCard;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;

interface MemberSubscribeProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberCard $card
     * @param Product $product
     * @return MemberSubscribeProduct|null
     */
    public function findByCardAndProduct(MemberCard $card, Product $product): ?MemberSubscribeProduct;

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
