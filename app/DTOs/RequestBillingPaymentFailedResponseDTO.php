<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;

class RequestBillingPaymentFailedResponseDTO
{
    public function __construct(
        public readonly string $status,
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
        $data = $response->object();
        return new self(
            status: "ABORTED",
            message: $data->message ?? "",
            code: $data->code ?? "Unknown",
            responseStatus: $response->status(),
            responseBody: $response->body(),
        );
    }
}
