<?php

namespace App\Services;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\MemberCashDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Enums\MemberCashTransactionTypeEnum;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\TossServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected MemberCashServiceInterface $cashService,
        protected MemberPaymentRepositoryInterface $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(GetMemberPaymentListDTO $DTO)
    {
        return $this->repository->getList($DTO);
    }

    /**
     * @inheritDoc
     */
    public function findFailedPayment(string $paymentId): ?MemberPayment
    {
        return $this->repository->findFailedPayment($paymentId);
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount(Member $member): int
    {
        return $this->repository->getTotalAmount($member);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaymentCount(Member $member): int
    {
        return $this->repository->getTotalPaymentCount($member);
    }

    /**
     * @inheritDoc
     */
    public function save(CreateMemberPaymentDTO $DTO): MemberPayment
    {
        return $this->repository->save($DTO);
    }

    /**
     * @inheritDoc
     */
    public function retry(PaymentRetryDTO $DTO): MemberPayment
    {
        $response = app(TossServiceInterface::class)
            ->requestBillingPayment($DTO->payment, $DTO->subscribe);

        return $this->process($DTO->payment, $response);
    }

    /**
     * @inheritDoc
     */
    public function process(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO|RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        return match ($DTO->status) {
            'DONE' => $this->processDone($payment, $DTO),
            'CANCELED', 'PARTIAL_CANCELED' => $this->processCancelled($payment, $DTO),
            default => $this->processAborted($payment, $DTO),
        };
    }

    private function processDone(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        $this->cashService->charge(new MemberCashDTO(
            $payment->member,
            $payment->amount,
            MemberCashTransactionTypeEnum::Increased,
            $payment->title,
            $payment->productable
        ));

        return $this->repository->updateDone($payment, $DTO);
    }

    private function processCancelled(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateDone($payment, $DTO);
    }

    private function processAborted(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateFailed($payment, $DTO);
    }
}
