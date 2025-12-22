<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateMemberPaymentDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Models\Interfaces\CardInterface;
use App\Models\Interfaces\PaymentInterface;

interface MemberPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $key
     * @return PaymentInterface|null
     */
    public function findByKey(string $key): ?PaymentInterface;

    /**
     * @param string $paymentId
     * @return PaymentInterface|null
     */
    public function findFailedPayment(string $paymentId): ?PaymentInterface;

    /**
     * @param CreateMemberPaymentDTO $DTO
     * @return PaymentInterface
     */
    public function save(CreateMemberPaymentDTO $DTO): PaymentInterface;

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
     */
    public function updateDone(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return PaymentInterface
     */
    public function updateCanceled(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param PortOneGetPaymentResponseDTO $DTO
     * @return PaymentInterface
     */
    public function updateFailed(PaymentInterface $payment, PortOneGetPaymentResponseDTO $DTO): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @param string $api
     * @return PaymentInterface
     */
    public function manuallySetFailed(PaymentInterface $payment, string $api): PaymentInterface;
}
