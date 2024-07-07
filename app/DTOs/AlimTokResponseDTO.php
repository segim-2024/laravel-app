<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AlimTokResponseDTO
{
    public function __construct(
        public string $responseCode,
        public string $responseBody,
        public string $code,
        public string $receivedAt,
        public string $message,
    ) {}

    public static function createFromResponse(Response $response): self
    {
        $data = $response->object();
        if (! $data) {
            Log::error("응답 정보 조회 이상 : {$response->body()}");
            throw new RuntimeException("응답 정보 조회 이상 : {$response->body()}", $response->status());
        }

        return new self(
            responseCode: $response->status(),
            responseBody: $response->body(),
            code: $data->code,
            receivedAt: $data->received_at,
            message: $data->message,
        );
    }
}
