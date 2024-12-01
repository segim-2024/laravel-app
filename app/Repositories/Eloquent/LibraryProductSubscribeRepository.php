<?php
namespace App\Repositories\Eloquent;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Models\LibraryProductSubscribe;
use App\Repositories\Interfaces\LibraryProductSubscribeRepositoryInterface;
use Illuminate\Support\Collection;

class LibraryProductSubscribeRepository extends BaseRepository implements LibraryProductSubscribeRepositoryInterface
{
    public function __construct(LibraryProductSubscribe $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function isExistsMemberSubscribe(string $memberId): bool
    {
        return LibraryProductSubscribe::where('member_id', $memberId)
            ->where('state', '!=', LibraryProductSubscribeStateEnum::Unsubscribe)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function findByMemberId(string $memberId): ?LibraryProductSubscribe
    {
        return LibraryProductSubscribe::where('member_id', $memberId)->first();
    }

    /**
     * @inheritDoc
     */
    public function findByMemberIdAndProductId(string $memberId, int $productId): ?LibraryProductSubscribe
    {
        return LibraryProductSubscribe::where('member_id', $memberId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptionsDueToday(): Collection
    {
        return LibraryProductSubscribe::whereDate('due_date', now()->toDateString())
            ->where('state', LibraryProductSubscribeStateEnum::Subscribe)
            ->get();
    }
}
