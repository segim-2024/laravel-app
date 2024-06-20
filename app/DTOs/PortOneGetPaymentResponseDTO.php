<?php

namespace App\DTOs;

use App\Enums\MemberPaymentStatusEnum;
use App\Exceptions\PortOneGetPaymentException;
use Exception;
use Illuminate\Http\Client\Response;

class PortOneGetPaymentResponseDTO
{
    public function __construct(
        public readonly MemberPaymentStatusEnum        $status,
        public readonly string                         $statusChangedAt,
        public readonly string                         $id,
        public readonly string                         $merchantId,
        public readonly string                         $storeId,
        public readonly string                         $updatedAt,
        public readonly string                         $orderName,
        public readonly PortOnePaymentAmountDTO        $amount,
        public readonly string                         $currency,
        public readonly ?PortOnePaymentMethodDTO       $method,
        public readonly ?string                        $scheduleId,
        public readonly ?string                        $billingKey,
        public readonly ?string                        $pgTxId,
        public readonly ?string                        $pgResponse,
        public readonly ?string                        $receiptUrl,
        public readonly ?string                        $paidAt,
        public readonly ?PortOnePaymentCancellationDTO $cancellations,
        public readonly ?string                        $cancelledAt,
        public readonly ?string                        $failedAt,
        public readonly mixed                          $cashReceipt,

        public readonly ?string                        $type,
        public readonly ?string                        $message,
        public readonly int                            $httpStatus,
        public readonly string                         $httpResponseBody,
    ) {}

    /**
     * @param Response $response
     * @return self
     * @throws PortOneGetPaymentException
     */
    public static function fromResponse(Response $response):self
    {
        $data = $response->object();
        if (! $data || ! isset($data->status)) {
            throw new PortOneGetPaymentException("결제 정보 조회 이상 : {$response->body()}", $response->status());
        }

        try {
            return new self(
                status: MemberPaymentStatusEnum::from($data->status),
                statusChangedAt: $data->statusChangedAt,
                id: $data->id,
                merchantId: $data->merchantId,
                storeId: $data->storeId,
                updatedAt: $data->updatedAt,
                orderName: $data->orderName,
                amount: PortOnePaymentAmountDTO::fromResponseObject($data->amount),
                currency: $data->currency,
                method: isset($data->method)
                    ? PortOnePaymentMethodDTO::fromResponseObject($data->method)
                    : null,
                scheduleId: $data->scheduleId ?? null,
                billingKey: $data->billingKey ?? null,
                pgTxId: $data->pgTxId ?? null,
                pgResponse: $data->pgResponse ?? null,
                receiptUrl: $data->receiptUrl ?? null,
                paidAt: $data->paidAt ?? null,
                cancellations: !empty($data->cancellations[0])
                    ? PortOnePaymentCancellationDTO::fromResponseObject($data->cancellations[0])
                    : null,
                cancelledAt: $data->cancelledAt ?? null,
                failedAt: $data->failedAt ?? null,
                cashReceipt: $data->cashReceipt ?? null,
                type: $data->type ?? "",
                message: $data->message ?? "",
                httpStatus: $response->status(),
                httpResponseBody: $response->body(),
            );
        } catch (Exception $exception) {
            throw new PortOneGetPaymentException("결제 정보 조회 이상 : {$response->body()}", $response->status());
        }
    }
}
