<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\RequestBillingPaymentResponseDTO;
use App\Models\Member;
use App\Models\MemberPayment;
use Illuminate\Support\Collection;

interface MemberPaymentServiceInterface
{
    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;

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
    public function process(MemberPayment $payment, RequestBillingPaymentResponseDTO $DTO): MemberPayment;
}
