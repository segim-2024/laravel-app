<?php

namespace App\Services\Interfaces;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\UnsubscribeProductDTO;
use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;
use Illuminate\Support\Collection;

interface MemberSubscribeProductServiceInterface
{
    /**
     * @param MemberInterface $member
     * @param ProductInterface $product
     * @return SubscribeProductInterface|null
     */
    public function findByMemberAndProduct(MemberInterface $member, ProductInterface $product): ?SubscribeProductInterface;

    /**
     * @param UpsertMemberSubscribeProductDTO $DTO
     * @return Collection
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO): Collection;

    /**
     * @param UnsubscribeProductDTO $DTO
     * @return void
     */
    public function unsubscribe(UnsubscribeProductDTO $DTO): void;

    /**
     * @param MemberInterface $member
     * @param SubscribeProductInterface $subscribeProduct
     * @return SubscribeProductInterface
     */
    public function updateLatestPayment(MemberInterface $member, SubscribeProductInterface $subscribeProduct): SubscribeProductInterface;

    /**
     * @param UpdateActivateMemberSubscribeProductDTO $DTO
     * @return SubscribeProductInterface
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): SubscribeProductInterface;

    /**
     * @param MemberInterface $member
     * @param MemberSubscribeProductLogDTO $DTO
     * @return void
     */
    public function logging(MemberInterface $member, MemberSubscribeProductLogDTO $DTO): void;

    /**
     * @param MemberInterface $member
     * @return bool
     */
    public function isExistsSubscribed(MemberInterface $member): bool;
}
