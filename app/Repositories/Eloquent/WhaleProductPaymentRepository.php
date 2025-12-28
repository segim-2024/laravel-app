<?php

namespace App\Repositories\Eloquent;

use App\DTOs\GetMemberPaymentListDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\WhaleMemberPayment;
use App\Models\WhaleProduct;
use App\Repositories\Interfaces\ProductPaymentRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class WhaleProductPaymentRepository extends BaseRepository implements ProductPaymentRepositoryInterface
{
    public function __construct(WhaleMemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberPaymentListDTO $DTO)
    {
        $query = WhaleMemberPayment::with(['card'])
            ->whereHasMorph('productable', WhaleProduct::class)
            ->when($DTO->start, fn($query) => $query->where('created_at', '>=', $DTO->start))
            ->when($DTO->end, fn($query) => $query->where('created_at', '<=', $DTO->end))
            ->when($DTO->keyword, fn($query) => $query->where('title', 'like', "%{$DTO->keyword}%"))
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->where('member_id', '=', $DTO->member->getMemberId());

        return DataTables::eloquent($query)->make();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(string $memberId): int
    {
        return WhaleMemberPayment::where('member_id', '=', $memberId)
            ->whereHasMorph('productable', WhaleProduct::class)
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
            ])
            ->sum('amount');
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(string $memberId): int
    {
        return WhaleMemberPayment::where('member_id', '=', $memberId)
            ->whereHasMorph('productable', WhaleProduct::class)
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->count();
    }
}