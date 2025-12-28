<?php

namespace App\Services\Interfaces;

use App\DTOs\PaymentCancelDTO;
use App\DTOs\PortOneBillingPaymentResponseDTO;
use App\DTOs\PortOneGetBillingKeyResponseDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\PortOneBillingPaymentException;
use App\Exceptions\PortOneGetBillingKeyException;
use App\Exceptions\PortOneGetPaymentException;
use App\Models\Interfaces\PaymentInterface;

interface PortOneServiceInterface
{
    /**
     * 빌링키 조회
     * 에러 401, 404, 500
     *
     * @throws PortOneGetBillingKeyException
     */
    public function getBillingKey(string $billingKey): PortOneGetBillingKeyResponseDTO;

    /**
     * 빌링키 결제 요청
     * 200 성공
     * 400, 401(Unauthorized), 403(Forbidden), 404(Not found key), 500
     *
     * @throws PortOneBillingPaymentException
     */
    public function requestPaymentByBillingKey(string $billingKey, PaymentInterface $payment): PortOneBillingPaymentResponseDTO;

    /**
     * 빌링키 삭제
     */
    public function deleteBillingKey(string $billingKey): bool;

    /**
     * 포트원 결제 정보 조회 (payment_id로 직접 조회)
     *
     * @throws PortOneGetPaymentException
     */
    public function getPaymentDetail(string $paymentId): PortOneGetPaymentResponseDTO;

    /**
     * @throws PortOneBillingPaymentException
     */
    public function cancel(PaymentCancelDTO $DTO): bool;
}
