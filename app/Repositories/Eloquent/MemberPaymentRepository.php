<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Carbon;

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
    public function findFailedPayment(string $paymentId): ?MemberPayment
    {
        return MemberPayment::where('payment_id', '=', $paymentId)
            ->where('state', '=', MemberPaymentStatusEnum::Failed)
            ->first();
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
        $payment->save();
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
        $payment->paid_at = Carbon::parse($DTO->paidAt)
            ->timezone('Asia/Seoul')
            ->format('Y-m-d H:i:s');
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
        $payment->cancelled_at = Carbon::parse($DTO->paidAt)
            ->timezone('Asia/Seoul')
            ->format('Y-m-d H:i:s');
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
        $payment->reason = $DTO->failure?->pgCode ?? 'UNKNOWN' ."\n". $DTO->failure?->pgMessage ?? 'UNKNOWN';
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
