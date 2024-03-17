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
}
