<?php

namespace App\DTOs;

use App\Exceptions\PortOneGetBillingKeyException;
use Illuminate\Http\Client\Response;

class PortOneGetBillingKeyResponseDTO
{
    public function __construct(
        public readonly string                      $billingKey,
        public readonly string                      $merchantId,
        public readonly string                      $storeId,
        public readonly ?PortOneBillingKeyMethodDTO $methods,
        public readonly string                      $issuedAt,
        public readonly int                         $httpStatus,
        public readonly string                      $httpResponseBody
    ) {}

    /**
     * @param Response $response
     * @return self
     * @throws PortOneGetBillingKeyException
     */
    public static function createFromResponse(Response $response):self
    {
        $data = $response->object();
        if (! $data || ! $response->json('billingKey') || ! $response->json('methods')) {
            throw new PortOneGetBillingKeyException("빌링키 정보 조회 이상 : {$response->body()}", $response->status());
        }

        return new self(
            billingKey: $data->billingKey,
            merchantId: $data->merchantId,
            storeId: $data->storeId,
            methods: isset($data->methods) && !empty($data->methods[0])
                ? PortOneBillingKeyMethodDTO::fromResponseObject($data->methods[0])
                : null,
            issuedAt: $data->issuedAt,
            httpStatus: $response->status(),
            httpResponseBody: $response->body(),
        );
    }
}
