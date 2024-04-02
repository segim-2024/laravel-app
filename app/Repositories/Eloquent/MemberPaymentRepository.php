<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MemberPaymentRepository extends BaseRepository implements MemberPaymentRepositoryInterface
{
    public function __construct(MemberPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(Member $member): Collection
    {
        return MemberPayment::with('card')
            ->where('member_id', '=', $member->mb_id)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): MemberPayment
    {
        $payment = new MemberPayment();
        $payment->payment_id = $DTO->paymentId;
        $payment->member_id = $DTO->member->mb_id;
        $payment->method = $DTO->method;
        $payment->title = $DTO->title;
        $payment->amount = $DTO->amount;
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
