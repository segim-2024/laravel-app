<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;

interface MemberPaymentServiceInterface
{
    /**
     * @param string $key
     * @return MemberPayment|null
     */
    public function findByKey(string $key): ?MemberPayment;

    /**
     * @param GetMemberPaymentListDTO $DTO
     */
    public function getList(GetMemberPaymentListDTO $DTO);

    /**
     * @param string $paymentId
     * @return MemberPayment|null
     */
    public function findFailedPayment(string $paymentId): ?MemberPayment;

    /**
     * @param Member $member
     * @return int
     */
    public function getTotalAmount(Member $member): int;

    /**
     * @param Member $member
     * @return int
     */
    public function getTotalPaymentCount(Member $member): int;

    /**
     * @param CreateMemberPaymentDTO $DTO
     * @return MemberPayment
     */
    public function save(CreateMemberPaymentDTO $DTO): MemberPayment;

    /**
     * @param PaymentRetryDTO $DTO
     * @return MemberPayment
     */
    public function retry(PaymentRetryDTO $DTO): MemberPayment;

    /**
     * @param PaymentCancelDTO $DTO
     * @return MemberPayment
     */
    public function cancel(PaymentCancelDTO $DTO): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param MemberCard $card
     * @return MemberPayment
     */
    public function updateCard(MemberPayment $payment, MemberCard $card): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function process(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO $DTO): MemberPayment;
}
