<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;
use JsonException;

class LibraryPaymentDoneApiResponseDTO
{
    public function __construct(
        public readonly int $httpStatusCode,
        public readonly bool $isSuccess,
        public readonly ?string $message = null,
        public readonly ?string $data = null,
    ) {}

    /**
     * @throws JsonException
     */
    public static function createFromPayloadAndResponse(array $payload, Response $response):self
    {
        $body = $response->object();
        $data = json_encode([
            'req' => $payload,
            'res' => $response->json(),
        ], JSON_THROW_ON_ERROR);

        return new self(
            httpStatusCode: $response->status(),
            isSuccess: $response->ok() && $body?->success ?? false,
            message: $body?->msg ?? null,
            data: $data,
        );
    }
}
