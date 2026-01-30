<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;

class PamusPointApiResponseDTO
{
    public function __construct(
        public readonly bool $isSuccess,
        public readonly ?string $message = null,
        public readonly ?string $mbId = null,
        public readonly ?int $convertedAmount = null,
        public readonly ?int $mileageBalance = null,
        public readonly ?int $pointBalance = null,
    ) {}

    public static function createFromResponse(Response $response): self
    {
        $body = $response->json();

        return new self(
            isSuccess: $response->successful() && ($body['result'] ?? '') === 'success',
            message: $body['msg'] ?? null,
            mbId: $body['mb_id'] ?? null,
            convertedAmount: isset($body['converted_amount']) ? (int) $body['converted_amount'] : null,
            mileageBalance: isset($body['mileage_balance']) ? (int) $body['mileage_balance'] : null,
            pointBalance: isset($body['point_balance']) ? (int) $body['point_balance'] : null,
        );
    }

    public static function createFromError(string $message): self
    {
        return new self(
            isSuccess: false,
            message: $message,
        );
    }
}