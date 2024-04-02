<?php

namespace App\DTOs;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CreateMemberCardResponseDTO
{
    public function __construct(
        public readonly string $mId,
        public readonly string $customerKey,
        public readonly string $authenticatedAt,
        public readonly string $method,
        public readonly string $billingKey,
        public readonly string $cardCompany,
        public readonly string $cardNumber,
        public readonly string $issuerCode,
        public readonly string $acquirerCode,
        public readonly string $cardType,
        public readonly string $ownerType,
    ) {}

    /**
     * @param Response $response
     * @return self
     */
    public static function createFromResponse(Response $response):self
    {
        $data = $response->object();
        if (! $data) {
            Log::error("빌링키 정보 조회 이상 : {$response->body()}");
            throw new RuntimeException("빌링키 정보 조회 이상 : {$response->body()}", $response->status());
        }

        return new self(
            mId: $data->mId,
            customerKey: $data->mId,
            authenticatedAt: $data->mId,
            method: $data->mId,
            billingKey: $data->billingKey,
            cardCompany: $data->cardCompany,
            cardNumber: $data->cardNumber,
            issuerCode: $data->card->issuerCode,
            acquirerCode: $data->card->acquirerCode,
            cardType: $data->card->cardType,
            ownerType: $data->card->ownerType,
        );
    }
}
