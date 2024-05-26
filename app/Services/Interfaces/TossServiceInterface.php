<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberCardResponseDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Models\MemberPayment;
use App\Models\MemberSubscribeProduct;

interface TossServiceInterface
{
    /**
     * @param string $customerKey
     * @param string $authKey
     * @return CreateMemberCardResponseDTO
     */
    public function createBillingKey(string $customerKey, string $authKey):CreateMemberCardResponseDTO;

    /**
     * @param MemberPayment $payment
     * @param MemberSubscribeProduct $subscribeProduct
     * @return RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO
     */
    public function requestBillingPayment(MemberPayment $payment, MemberSubscribeProduct $subscribeProduct): RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO;

    /**
     * @param PaymentCancelDTO $DTO
     * @return TossPaymentResponseDTO
     */
    public function requestCancel(PaymentCancelDTO $DTO): TossPaymentResponseDTO;
}
