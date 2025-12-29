<?php

namespace App\Services\Interfaces;

use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Models\Interfaces\PaymentInterface;

interface ProductPaymentServiceInterface
{
    /**
     * 이캐쉬 결제 성공에 따른 처리
     *
     * @param PaymentInterface $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return void
     */
    public function processPaid(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): void;

    /**
     * 이캐쉬 결제 실패에 따른 처리
     *
     * @param PaymentInterface $payment
     * @return void
     */
    public function processFailed(PaymentInterface $payment): void;
}
