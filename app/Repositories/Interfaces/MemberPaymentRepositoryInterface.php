<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberPayment;
use Illuminate\Support\Collection;

interface MemberPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $key
     * @return MemberPayment|null
     */
    public function findByKey(string $key): ?MemberPayment;

    /**
     * @param GetMemberPaymentListDTO $DTO
     * @return Collection
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
     * @param MemberPayment $payment
     * @param MemberCard $card
     * @return MemberPayment
     */
    public function updateCard(MemberPayment $payment, MemberCard $card):MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function updateDone(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function updateCanceled(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function updateFailed(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param string $api
     * @return MemberPayment
     */
    public function manuallySetFailed(MemberPayment $payment, string $api): MemberPayment;
}
