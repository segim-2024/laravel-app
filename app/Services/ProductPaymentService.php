<?php

namespace App\Services;

use App\DTOs\MemberCashDTO;
use App\DTOs\MemberSubscribeProductLogDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Enums\MemberCashTransactionTypeEnum;
use App\Jobs\FailedSubscribePaymentSendAlimTokJob;
use App\Models\MemberPayment;
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
    public function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): void
    {
        $subscribeProduct = $this->subscribeService->findByMemberAndProduct($payment->member, $payment->productable);
        if ($subscribeProduct) {
            $this->subscribeService->updateLatestPayment($payment->member, $subscribeProduct);
            $this->subscribeService->logging($payment->member, MemberSubscribeProductLogDTO::payment($subscribeProduct));
        }

        $this->cashService->charge(new MemberCashDTO(
            $payment->member,
            $payment->amount,
            MemberCashTransactionTypeEnum::Increased,
            $payment->title,
            $payment->productable
        ));
    }

    /**
     * @inheritDoc
     */
    public function processFailed(MemberPayment $payment): void
    {
        if ($payment->member->mb_hp) {
            FailedSubscribePaymentSendAlimTokJob::dispatch($payment->member)->afterCommit();
        }
    }
}
