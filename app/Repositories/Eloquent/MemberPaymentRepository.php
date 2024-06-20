<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Carbon;
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
    public function findByKey(string $key): ?MemberPayment
    {
        return MemberPayment::where('payment_id', '=', $key)->first();
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
    public function findFailedPayment(string $paymentId): ?MemberPayment
    {
        return MemberPayment::where('payment_id', '=', $paymentId)
            ->where('state', '=', MemberPaymentStatusEnum::Failed)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return MemberPayment::where('member_id', '=', $member->mb_id)
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
    public function getTotalPaymentCount(Member $member): int
    {
        return MemberPayment::where('member_id', '=', $member->mb_id)
            ->whereIn('state', [
                MemberPaymentStatusEnum::Paid,
                MemberPaymentStatusEnum::Cancelled,
                MemberPaymentStatusEnum::PartialCancelled,
                MemberPaymentStatusEnum::Failed,
            ])
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
    public function updateCard(MemberPayment $payment, MemberCard $card):MemberPayment
    {
        $payment->card_id = $card->id;
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateDone(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        $payment->tx_id = $DTO->pgTxId;
        $payment->state = $DTO->status;
        $payment->api = $DTO->httpResponseBody;
        $payment->receipt_url = $DTO->receiptUrl ?? null;
        $payment->paid_at = Carbon::parse($DTO->paidAt);
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateCanceled(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        $payment->tx_id = $DTO->pgTxId ?? "";
        $payment->state = $DTO->status;
        $payment->cancelled_amount = $DTO->cancellations?->totalAmount ?? 0;
        $payment->reason = $DTO->cancellations?->reason ?? 'UNKNOWN';
        $payment->receipt_url = $DTO->receiptUrl ?? null;
        $payment->cancelled_at = Carbon::parse($DTO->cancelledAt);
        $payment->api = $DTO->httpResponseBody;
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateFailed(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        $payment->tx_id = $DTO->pgTxId ?? "";
        $payment->state = $DTO->status;
        $payment->reason = $portOnePaymentDTO->failure?->pg_code ?? 'UNKNOWN' ."\n". $portOnePaymentDTO->failure?->pg_message ?? 'UNKNOWN';
        $payment->api = $DTO->httpResponseBody;
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function manuallySetFailed(MemberPayment $payment, string $api): MemberPayment
    {
        $payment->state = MemberPaymentStatusEnum::Failed;
        $payment->api = $api;
        $payment->save();
        return $payment;
    }
}
