<?php
namespace App\Repositories\Eloquent;

use App\DTOs\GetLibraryPaymentListDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\LibraryProduct;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\LibraryPaymentRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class LibraryPaymentRepository extends BaseRepository implements LibraryPaymentRepositoryInterface
{
    public function __construct(MemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetLibraryPaymentListDTO $DTO)
    {
        $query = MemberPayment::with([
                'card'
            ])
            ->whereHasMorph('productable', LibraryProduct::class)
            ->when($DTO->start, fn($query) => $query->where('created_at', '>=', $DTO->start))
            ->when($DTO->end, fn($query) => $query->where('created_at', '<=', $DTO->end))
            ->when($DTO->keyword, fn($query) => $query->where('title', 'like', "%{$DTO->keyword}%"))
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->where('member_id', '=', $DTO->memberId);

        return DataTables::eloquent($query)->make();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(string $memberId): int
    {
        return MemberPayment::where('member_id', '=', $memberId)
            ->whereHasMorph('productable', LibraryProduct::class)
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
            ->whereHasMorph('productable', [LibraryProduct::class])
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
            ->count();
    }
}
