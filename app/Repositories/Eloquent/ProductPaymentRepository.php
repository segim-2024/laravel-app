<?php
namespace App\Repositories\Eloquent;

use App\DTOs\GetMemberPaymentListDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\MemberPayment;
use App\Models\Product;
use App\Repositories\Interfaces\ProductPaymentRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class ProductPaymentRepository extends BaseRepository implements ProductPaymentRepositoryInterface
{
    public function __construct(MemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetMemberPaymentListDTO $DTO)
    {
        $query = MemberPayment::with([
                'card'
            ])
            ->whereHasMorph('productable', Product::class)
            ->when($DTO->start, fn($query) => $query->where('created_at', '>=', $DTO->start))
            ->when($DTO->end, fn($query) => $query->where('created_at', '<=', $DTO->end))
            ->when($DTO->keyword, fn($query) => $query->where('title', 'like', "%{$DTO->keyword}%"))
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->where('member_id', '=', $DTO->member->mb_id);

        return DataTables::eloquent($query)->make();
    }


    /**
     * @inheritDoc
     */
    public function getTotalAmount(string $memberId): int
    {
        return MemberPayment::where('member_id', '=', $memberId)
            ->whereHasMorph('productable', Product::class)
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
        return MemberPayment::where('member_id', '=', $memberId)
            ->whereHasMorph('productable', Product::class)
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->count();
    }
}
