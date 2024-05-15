<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberCardResponseDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
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
     * @return RequestBillingPaymentFailedResponseDTO|RequestBillingPaymentResponseDTO
     */
    public function requestBillingPayment(MemberPayment $payment, MemberSubscribeProduct $subscribeProduct): RequestBillingPaymentFailedResponseDTO|RequestBillingPaymentResponseDTO;
}
