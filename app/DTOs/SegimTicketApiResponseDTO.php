<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;

class SegimTicketApiResponseDTO
{
    public function __construct(
        public readonly int $httpStatusCode,
        public readonly bool $isSuccess,
        public readonly ?string $message = null,
        public readonly ?string $data = null,
    ) {}

    public static function createFromPayloadAndResponse(array $payload, Response $response):self
    {
        $body = $response->object();
        $data = json_encode([
            'req' => $payload,
            'res' => $response->body(),
        ], JSON_THROW_ON_ERROR);

        return new self(
            httpStatusCode: $response->status(),
            isSuccess: $response->successful(),
            message: $body?->msg ?? null,
            data: $data,
        );
    }
}
