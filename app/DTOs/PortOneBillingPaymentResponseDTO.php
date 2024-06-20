<?php

namespace App\DTOs;

use App\Exceptions\PortOneBillingPaymentException;
use Illuminate\Http\Client\Response;

class PortOneBillingPaymentResponseDTO
{
    public function __construct(
        public readonly string $paidAt,
        public readonly string $pgTxId,
        public readonly int    $status,
        public readonly string $responseBody,
    ) {}

    /**
     * @param Response $response
     * @return self
     * @throws PortOneBillingPaymentException
     */
    public static function createFromResponse(Response $response):self
    {
        $data = $response->object();
        if (! $data || ! isset($data->payment)) {
            throw new PortOneBillingPaymentException("빌링키 결제 요청 이상 : {$response->body()}", $response->status());
        }

        return new self(
            paidAt: $data->payment->paidAt,
            pgTxId: $data->payment->pgTxId,
            status: $response->status(),
            responseBody: $response->body(),
        );
    }
}
