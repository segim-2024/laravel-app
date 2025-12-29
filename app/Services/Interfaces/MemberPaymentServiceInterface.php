<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\GetMemberPaymentListDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\PaymentRetryDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Exceptions\PaymentIsNotFailedException;
use App\Exceptions\PortOneBillingPaymentException;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\MemberInterface;
use App\Models\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface MemberPaymentServiceInterface
{
    /**
     * @param string $key
     * @return PaymentInterface|null
     */
    public function findByKey(string $key): ?PaymentInterface;

    /**
     * isWhale 플래그로 결제 정보 조회
     *
     * @param string $key
     * @param bool $isWhale
     * @return PaymentInterface|null
     */
    public function findByKeyWithIsWhale(string $key, bool $isWhale = false): ?PaymentInterface;

    /**
     * @param GetMemberPaymentListDTO $DTO
     */
    public function getList(GetMemberPaymentListDTO $DTO);

    /**
     * @param string $paymentId
     * @return PaymentInterface|null
     */
    public function findFailedPayment(string $paymentId): ?PaymentInterface;

    /**
     * isWhale 플래그로 실패한 결제 정보 조회
     *
     * @param string $paymentId
     * @param bool $isWhale
     * @return PaymentInterface|null
     */
    public function findFailedPaymentWithIsWhale(string $paymentId, bool $isWhale = false): ?PaymentInterface;

    /**
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalAmount(MemberInterface $member): int;

    /**
     * @param MemberInterface $member
     * @return int
     */
    public function getTotalPaymentCount(MemberInterface $member): int;

    /**
     * @param CreateMemberPaymentDTO $DTO
     * @return PaymentInterface
     */
    public function save(CreateMemberPaymentDTO $DTO): PaymentInterface;

    /**
     * @param PaymentRetryDTO $DTO
     * @return PaymentInterface
     * @throws PortOneBillingPaymentException
     */
    public function retry(PaymentRetryDTO $DTO): PaymentInterface;

    /**
     * @param PaymentCancelDTO $DTO
     * @return PaymentInterface
     * @throws PortOneBillingPaymentException
     */
    public function cancel(PaymentCancelDTO $DTO): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param CardInterface $card
     * @return PaymentInterface
     */
    public function updateCard(PaymentInterface $payment, CardInterface $card): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return PaymentInterface
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function process(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param string $api
     * @return PaymentInterface
     * @throws LibraryProductSubscribeNotFoundException
     */
    public function manuallySetFailed(PaymentInterface $payment, string $api): PaymentInterface;

    /**
     * @param string $paymentId
     * @return void
     * @throws ModelNotFoundException
     * @throws PaymentIsNotFailedException
     */
    public function deleteFailedPayment(string $paymentId): void;

    /**
     * isWhale 플래그로 실패한 결제 정보 삭제
     *
     * @param string $paymentId
     * @param bool $isWhale
     * @return void
     * @throws ModelNotFoundException
     * @throws PaymentIsNotFailedException
     */
    public function deleteFailedPaymentWithIsWhale(string $paymentId, bool $isWhale = false): void;
}
