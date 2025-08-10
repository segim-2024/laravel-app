<?php
namespace App\Repositories\Eloquent;

use App\DTOs\UpdateActivateMemberSubscribeProductDTO;
use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\Member;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;

class MemberSubscribeProductRepository extends BaseRepository implements MemberSubscribeProductRepositoryInterface
{
    public function __construct(MemberSubscribeProduct $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByMemberAndProduct(Member $member, Product $product): ?MemberSubscribeProduct
    {
        return MemberSubscribeProduct::where('member_id', '=', $member->mb_id)
            ->where('product_id', '=', $product->id)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function upsertCard(UpsertMemberSubscribeProductDTO $DTO): MemberSubscribeProduct
    {
        return MemberSubscribeProduct::updateOrCreate(
            [
                'product_id' => $DTO->product->id,
                'member_id' => $DTO->member->mb_id,
            ],
            [
                'product_id' => $DTO->product->id,
                'member_id' => $DTO->member->mb_id,
                'card_id' => $DTO->card->id,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function updateLatestPayment(MemberSubscribeProduct $subscribeProduct): MemberSubscribeProduct
    {
        $subscribeProduct->latest_payment_at = now();
        $subscribeProduct->save();
        return $subscribeProduct;
    }

    /**
     * @inheritDoc
     */
    public function updateActivate(UpdateActivateMemberSubscribeProductDTO $DTO): MemberSubscribeProduct
    {
        $subscribe = $DTO->subscribe;
        $subscribe->is_activated = $DTO->isActive;
        $subscribe->save();
        return $subscribe;
    }

    /**
     * @inheritDoc
     */
    public function isExistsSubscribe(Member $member): bool
    {
        return MemberSubscribeProduct::where('member_id', $member->mb_id)->exists();
    }
}
