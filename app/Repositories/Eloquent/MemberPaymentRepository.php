<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class MemberPaymentRepository extends BaseRepository implements MemberPaymentRepositoryInterface
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
            ->when($DTO->start, fn($query) => $query->where('created_at', '>=', $DTO->start))
            ->when($DTO->end, fn($query) => $query->where('created_at', '<=', $DTO->end))
            ->when($DTO->keyword, fn($query) => $query->where('title', 'like', "%{$DTO->keyword}%"))
            ->whereIn('state', ['DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED'])
            ->where('member_id', '=', $DTO->member->mb_id);

        return DataTables::eloquent($query)->make();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return MemberPayment::where('member_id', '=', $member->mb_id)
            ->whereIn('state', ['DONE', 'CANCELED', 'PARTIAL_CANCELED'])
            ->sum('amount');
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(Member $member): int
    {
        return MemberPayment::where('member_id', '=', $member->mb_id)
            ->whereIn('state', ['DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED'])
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): MemberPayment
    {
        $payment = new MemberPayment();
        $payment->payment_id = $DTO->paymentId;
        $payment->member_id = $DTO->member->mb_id;
        $payment->card_id = $DTO->card?->id ?? null;
        $payment->method = $DTO->method;
        $payment->title = $DTO->title;
        $payment->amount = $DTO->amount;
        $payment->productable()->associate($DTO->product);
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateDone(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        $payment->state = $DTO->status;
        $payment->api = $DTO->responseBody;
        $payment->receipt_url = $DTO->receiptUrl ?? null;
        $payment->paid_at = Carbon::parse($DTO->approvedAt);
        $payment->save();
        return $payment;
    }
}
