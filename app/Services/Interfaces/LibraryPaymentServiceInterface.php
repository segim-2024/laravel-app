<?php

namespace App\Services\Interfaces;

use App\DTOs\GetLibraryPaymentListDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Models\MemberPayment;

interface LibraryPaymentServiceInterface
{
    /**
     * @param GetLibraryPaymentListDTO $DTO
     */
    public function getList(GetLibraryPaymentListDTO $DTO);

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalAmount(string $memberId): int;

    /**
     * @param string $memberId
     * @return int
     */
    public function getTotalPaymentCount(string $memberId): int;

    /**
     * 라이브러리 결제 성공에 따른 처리
     *
     * @param MemberPayment $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return void
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function processPaid(MemberPayment $payment, PortOneGetPaymentResponseDTO $DTO): void;

    /**
     * 라이브러리 결제 실패에 따른 처리
     *
     * @param MemberPayment $payment
     * @return void
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function processFailed(MemberPayment $payment): void;
}
