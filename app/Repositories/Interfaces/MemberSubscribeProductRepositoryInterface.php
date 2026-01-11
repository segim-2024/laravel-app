<?php
namespace App\Repositories\Interfaces;

use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;

interface MemberSubscribeProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberInterface $member
     * @param ProductInterface $product
     * @return SubscribeProductInterface|null
     */
    public function findByMemberAndProduct(MemberInterface $member, ProductInterface $product): ?SubscribeProductInterface;

    /**
     * @param UpsertMemberSubscribeProductDTO $DTO
     * @return SubscribeProductInterface
     */
    public function upsertCard(UpsertMemberSubscribeProductDTO $DTO): SubscribeProductInterface;

    /**
     * @param SubscribeProductInterface $subscribeProduct
     * @return SubscribeProductInterface
     */
    public function updateLatestPayment(SubscribeProductInterface $subscribeProduct): SubscribeProductInterface;

    /**
     * @param UpdateActivateMemberSubscribeProductDTO $DTO
     * @return SubscribeProductInterface
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): SubscribeProductInterface;

    /**
     * @param MemberInterface $member
     * @return bool
     */
    public function isExistsSubscribe(MemberInterface $member): bool;

    /**
     * @param SubscribeProductInterface $subscribe
     * @param bool $isStarted
     * @return SubscribeProductInterface
     */
    public function updateIsStarted(SubscribeProductInterface $subscribe, bool $isStarted): SubscribeProductInterface;
}
