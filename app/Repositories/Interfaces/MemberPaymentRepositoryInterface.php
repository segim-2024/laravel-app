<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberPayment;
use Illuminate\Support\Collection;

interface MemberPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param GetMemberPaymentListDTO $DTO
     * @return Collection
     */
    public function getList(GetMemberPaymentListDTO $DTO);

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
     * @param RequestBillingPaymentResponseDTO $DTO
     * @return MemberPayment
     */
    public function updateDone(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment;

    /**
     * @param MemberPayment $payment
     * @param RequestBillingPaymentFailedResponseDTO $DTO
     * @return MemberPayment
     */
    public function updateFailed(MemberPayment $payment, RequestBillingPaymentFailedResponseDTO $DTO): MemberPayment;
}
