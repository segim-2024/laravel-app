<?php

namespace App\Repositories\Eloquent;

use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\ProductInterface;
use App\Models\Interfaces\SubscribeProductInterface;
use App\Models\WhaleMemberSubscribeProduct;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;

class WhaleMemberSubscribeProductRepository extends BaseRepository implements MemberSubscribeProductRepositoryInterface
{
    public function __construct(WhaleMemberSubscribeProduct $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByMemberAndProduct(MemberInterface $member, ProductInterface $product): ?SubscribeProductInterface
    {
        return WhaleMemberSubscribeProduct::where('member_id', '=', $member->getMemberId())
            ->where('product_id', '=', $product->getId())
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function upsertCard(UpsertMemberSubscribeProductDTO $DTO): SubscribeProductInterface
    {
        return WhaleMemberSubscribeProduct::updateOrCreate(
            [
                'product_id' => $DTO->product->getId(),
                'member_id' => $DTO->member->getMemberId(),
            ],
            [
                'product_id' => $DTO->product->getId(),
                'member_id' => $DTO->member->getMemberId(),
                'card_id' => $DTO->card->getId(),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function updateLatestPayment(SubscribeProductInterface $subscribeProduct): SubscribeProductInterface
    {
        $subscribeProduct->latest_payment_at = now();
        $subscribeProduct->save();
        return $subscribeProduct;
    }

    /**
     * @inheritDoc
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): SubscribeProductInterface
    {
        $subscribe = $DTO->subscribe;
        $subscribe->is_activated = $DTO->isActive;
        $subscribe->save();
        return $subscribe;
    }

    /**
     * @inheritDoc
     */
    public function isExistsSubscribe(MemberInterface $member): bool
    {
        return WhaleMemberSubscribeProduct::where('member_id', $member->getMemberId())->exists();
    }

    /**
     * @inheritDoc
     */
    public function updateIsStarted(SubscribeProductInterface $subscribe, bool $isStarted): SubscribeProductInterface
    {
        $subscribe->is_started = $isStarted;
        $subscribe->save();
        return $subscribe;
    }
}