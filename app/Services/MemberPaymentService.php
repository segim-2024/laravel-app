<?php

namespace App\Services;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\MemberCashDTO;
use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Enums\MemberCashTransactionTypeEnum;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\TossServiceInterface;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected MemberCashServiceInterface $cashService,
        protected MemberSubscribeProductServiceInterface $subscribeService,
        protected MemberPaymentRepositoryInterface $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function findByKey(string $key): ?MemberPayment
    {
        return $this->repository->findByKey($key);
    }

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
        if ($DTO->payment->card_id !== $DTO->subscribe->card_id) {
            $DTO->payment = $this->updateCard($DTO->payment, $DTO->subscribe->card);
        }

        $this->subscribeService->updateLatestPayment($DTO->subscribe);
        $this->subscribeService->logging(MemberSubscribeProductLogDTO::payment($DTO->subscribe));
        $response = app(TossServiceInterface::class)
            ->requestBillingPayment($DTO->payment, $DTO->subscribe);
        return $this->process($DTO->payment, $response);
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentCancelDTO $DTO): MemberPayment
    {
        $response = app(TossServiceInterface::class)->requestCancel($DTO);
        return $this->process($DTO->payment, $response);
    }

    /**
     * @inheritDoc
     */
    public function updateCard(MemberPayment $payment, MemberCard $card): MemberPayment
    {
        return $this->repository->updateCard($payment, $card);
    }

    /**
     * @inheritDoc
     */
    public function process(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO $DTO): MemberPayment
    {
        return match ($DTO->status) {
            'DONE' => $this->processDone($payment, $DTO),
            'CANCELED', 'PARTIAL_CANCELED' => $this->processCancelled($payment, $DTO),
            default => $this->processAborted($payment, $DTO),
        };
    }

    private function processDone(MemberPayment $payment, TossPaymentResponseDTO $DTO): MemberPayment
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

    private function processCancelled(MemberPayment $payment, TossPaymentResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateCanceled($payment, $DTO);
    }

    private function processAborted(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO $DTO): MemberPayment
    {
        return $this->repository->updateFailed($payment, $DTO);
    }
}
