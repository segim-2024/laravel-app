<?php

namespace App\Services;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\MemberCashDTO;
use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberCashTransactionTypeEnum;
use App\Enums\MemberPaymentStatusEnum;
use App\Jobs\FailedSubscribePaymentSendAlimTokJob;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;

class MemberPaymentService implements MemberPaymentServiceInterface {
    public function __construct(
        protected MemberCashServiceInterface $cashService,
        protected MemberSubscribeProductServiceInterface $subscribeService,
        protected MemberPaymentRepositoryInterface $repository,
        protected PortOneServiceInterface $portOneService
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

        $this->portOneService->requestPaymentByBillingKey($DTO->subscribe->card->key, $DTO->payment);
        return $DTO->payment;
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentCancelDTO $DTO): MemberPayment
    {
        $this->portOneService->cancel($DTO);
        return $DTO->payment;
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
    public function process(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        return match ($DTO->status) {
            MemberPaymentStatusEnum::Ready, MemberPaymentStatusEnum::Unpaid => $payment,
            MemberPaymentStatusEnum::Paid => $this->processPaid($payment, $DTO),
            MemberPaymentStatusEnum::Cancelled, MemberPaymentStatusEnum::PartialCancelled => $this->processCancelled($payment, $DTO),
            default => $this->processFailed($payment, $DTO),
        };
    }

    private function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        if ($payment->state === $DTO->status) {
            return $payment;
        }

        $subscribeProduct = $this->subscribeService->findByMemberAndProduct($payment->member, $payment->productable);
        if ($subscribeProduct) {
            $this->subscribeService->updateLatestPayment($subscribeProduct);
            $this->subscribeService->logging(MemberSubscribeProductLogDTO::payment($subscribeProduct));
        }

        $this->cashService->charge(new MemberCashDTO(
            $payment->member,
            $payment->amount,
            MemberCashTransactionTypeEnum::Increased,
            $payment->title,
            $payment->productable
        ));

        return $this->repository->updateDone($payment, $DTO);
    }

    private function processCancelled(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        if ($payment->state === MemberPaymentStatusEnum::Cancelled) {
            return $payment;
        }

        return $this->repository->updateCanceled($payment, $DTO);
    }

    private function processFailed(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment
    {
        if ($payment->member->mb_hp) {
            FailedSubscribePaymentSendAlimTokJob::dispatch($payment->member)
                ->afterCommit();
        }

        return $this->repository->updateFailed($payment, $DTO);
    }

    /**
     * @inheritDoc
     */
    public function manuallySetFailed(MemberPayment $payment, string $api): MemberPayment
    {
        if ($payment->member->mb_hp) {
            FailedSubscribePaymentSendAlimTokJob::dispatch($payment->member)
                ->afterCommit();
        }

        return $this->repository->manuallySetFailed($payment, $api);
    }
}
