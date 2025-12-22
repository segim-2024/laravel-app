<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberPaymentStatusEnum;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\PaymentInterface;
use App\Models\WhaleMemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Carbon;

class WhaleMemberPaymentRepository extends BaseRepository implements MemberPaymentRepositoryInterface
{
    public function __construct(WhaleMemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByKey(string $key): ?PaymentInterface
    {
        return WhaleMemberPayment::where('payment_id', '=', $key)->first();
    }

    /**
     * @inheritDoc
     */
    public function findFailedPayment(string $paymentId): ?PaymentInterface
    {
        return WhaleMemberPayment::where('payment_id', '=', $paymentId)
            ->where('state', '=', MemberPaymentStatusEnum::Failed)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): PaymentInterface
    {
        $payment = new WhaleMemberPayment();
        $payment->payment_id = $DTO->paymentId;
        $payment->member_id = $DTO->member->getMemberId();
        $payment->card_id = $DTO->card?->getId() ?? null;
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
    public function updateCard(PaymentInterface $payment, CardInterface $card): PaymentInterface
    {
        $payment->card_id = $card->getId();
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateDone(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
    {
        $payment->tx_id = $DTO->pgTxId;
        $payment->state = $DTO->status;
        $payment->api = $DTO->httpResponseBody;
        $payment->receipt_url = $DTO->receiptUrl ?? null;
        $payment->payment_key = $DTO->paymentKey ?? null;
        $payment->paid_at = Carbon::parse($DTO->paidAt)
            ->timezone('Asia/Seoul')
            ->format('Y-m-d H:i:s');
        $payment->save();
        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function updateCanceled(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
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
    public function updateFailed(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface
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
    public function manuallySetFailed(PaymentInterface $payment, string $api): PaymentInterface
    {
        $payment->state = MemberPaymentStatusEnum::Failed;
        $payment->api = $api;
        $payment->save();
        return $payment;
    }
}