<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class RequestBillingPaymentFailedResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $code,
        public readonly string $responseStatus,
        public readonly string $responseBody
    ) {}


    /**
     * @param Response $response
     * @return self
     */
    public static function createFromResponse(Response $response): self
    {
        Log::error($response->status());
        Log::error($response->body());
        $data = $response->object();

        return new self(
            message: $data->message ?? "",
            code: $data->code ?? "Unknown",
            responseStatus: $response->status(),
            responseBody: $response->body(),
        );
    }
}
