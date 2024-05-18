<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use App\Models\MemberSubscribeProduct;
use Illuminate\Support\Collection;

interface MemberPaymentServiceInterface
{
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
     * @param MemberPayment $payment
     * @param MemberCard $card
     * @return MemberPayment
     */
    public function updateCard(MemberPayment $payment, MemberCard $card): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param RequestBillingPaymentFailedResponseDTO|RequestBillingPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function process(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO|RequestBillingPaymentResponseDTO $DTO): MemberPayment;
}
