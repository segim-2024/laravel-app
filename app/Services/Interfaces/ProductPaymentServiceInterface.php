<?php

namespace App\Services\Interfaces;

use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Models\MemberPayment;

interface ProductPaymentServiceInterface
{
    /**
     * 이캐쉬 결제 성공에 따른 처리
     *
     * @param MemberPayment $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return void
     */
    public function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): void;

    /**
     * 이캐쉬 결제 실패에 따른 처리
     *
     * @param MemberPayment $payment
     * @return void
     */
    public function processFailed(MemberPayment $payment): void;
}
