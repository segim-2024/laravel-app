<?php
namespace App\Repositories\Eloquent;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberSubscribeProduct;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class MemberSubscribeProductRepository extends BaseRepository implements MemberSubscribeProductRepositoryInterface
{
    public function __construct(MemberSubscribeProduct $model)
    {
        parent::__construct($model);
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
}
