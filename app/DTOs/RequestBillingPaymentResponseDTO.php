<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RequestBillingPaymentResponseDTO
{
    public function __construct(
        public readonly string $mId,
        public readonly string $version,
        public readonly string $paymentKey,
        public readonly string $status,
        public readonly string $lastTransactionKey,
        public readonly string $orderId,
        public readonly string $orderName,
        public readonly string $requestedAt,
        public readonly string $approvedAt,
        public readonly string $type,
        public readonly ?string $receiptUrl,
        public readonly ?string $checkoutUrl,
        public readonly string $currency,
        public readonly int $totalAmount,
        public readonly int $balanceAmount,
        public readonly int $suppliedAmount,
        public readonly int $vat,
        public readonly int $taxFreeAmount,
        public readonly int $taxExemptionAmount,
        public readonly string $method,
        public readonly string $responseBody,
        public readonly int $responseStatus,
    ) {}

    /**
     * @param Response $response
     * @return self
     */
    public static function createFromResponse(Response $response): self
    {
        Log::info($response->body());
        $data = $response->object();
        if (!$data) {
            Log::error("응답 정보 조회 이상 : {$response->body()}");
            throw new RuntimeException("응답 정보 조회 이상 : {$response->body()}", $response->status());
        }

        return new self(
            mId: $data->mId,
            version: $data->version,
            paymentKey: $data->paymentKey,
            status: $data->status,
            lastTransactionKey: $data->lastTransactionKey,
            orderId: $data->orderId,
            orderName: $data->orderName,
            requestedAt: $data->requestedAt,
            approvedAt: $data->approvedAt,
            type: $data->type,
            receiptUrl: $data->receipt->url ?? null,
            checkoutUrl: $data->checkout->url ?? null,
            currency: $data->currency,
            totalAmount: $data->totalAmount,
            balanceAmount: $data->balanceAmount,
            suppliedAmount: $data->suppliedAmount,
            vat: $data->vat,
            taxFreeAmount: $data->taxFreeAmount,
            taxExemptionAmount: $data->taxExemptionAmount,
            method: $data->method,
            responseBody: $response->body(),
            responseStatus: $response->status(),
        );
    }
}
