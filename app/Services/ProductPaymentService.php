<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberCashTransactionTypeEnum;
use App\Jobs\FailedSubscribePaymentSendAlimTokJob;
use App\Models\Interfaces\PaymentInterface;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductPaymentServiceInterface;

class ProductPaymentService implements ProductPaymentServiceInterface {
    public function __construct(
        protected MemberSubscribeProductServiceInterface $subscribeService,
        protected MemberCashServiceInterface $cashService,
    ) {}

    /**
     * @inheritDoc
     */
    public function processPaid(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): void
    {
        $member = $payment->getMember();
        $subscribeProduct = $this->subscribeService->findByMemberAndProduct($member, $payment->productable);
        if ($subscribeProduct) {
            $this->subscribeService->updateLatestPayment($member, $subscribeProduct);
            $this->subscribeService->logging($member, MemberSubscribeProductLogDTO::payment($subscribeProduct));
        }

        $this->cashService->charge(new MemberCashDTO(
            $member,
            $payment->getAmount(),
            MemberCashTransactionTypeEnum::Increased,
            $payment->getTitle(),
            $payment->productable
        ));
    }

    /**
     * @inheritDoc
     */
    public function processFailed(PaymentInterface $payment): void
    {
        $member = $payment->getMember();
        if ($member->mb_hp && ! $member->isWhale()) {
            FailedSubscribePaymentSendAlimTokJob::dispatch($member)->afterCommit();
        }
    }
}
