<?php

namespace App\DTOs;

use App\Http\Requests\TossWebHookRequest;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TossPaymentResponseDTO
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
        /* @var null|Collection|TossPaymentCancelDTO[]  */
        public readonly ?Collection $cancels
    ) {}

    /**
     * @param Response $response
     * @return self
     */
    public static function createFromResponse(Response $response): self
    {
        $data = $response->object();
        if (!$data) {
            Log::error("응답 정보 조회 이상 : {$response->body()}");
            throw new RuntimeException("응답 정보 조회 이상 : {$response->body()}", $response->status());
        }

        if (isset($data->cancels)) {
            $cancels = collect($data->cancels)->map(fn ($cancel) => TossPaymentCancelDTO::createFromPaymentResponse($cancel));
        } else {
            $cancels = collect([]);
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
            cancels: $cancels,
        );
    }

    public static function createFromWebHook(TossWebHookRequest $request): self
    {
        try {
            $data = $request->validated('data');

            if (isset($data['cancels'])) {
                $cancels = collect($data['cancels'])->map(fn ($cancel) => TossPaymentCancelDTO::createFromPaymentWebHook($cancel));
            } else {
                $cancels = collect([]);
            }

            return new self(
                mId: $data['mId'],
                version: $data['version'],
                paymentKey: $data['paymentKey'],
                status: $data['status'],
                lastTransactionKey: $data['lastTransactionKey'],
                orderId: $data['orderId'],
                orderName: $data['orderName'],
                requestedAt: $data['requestedAt'],
                approvedAt: $data['approvedAt'],
                type: $data['type'],
                receiptUrl: $data['receipt']['url'] ?? null,
                checkoutUrl: $data['checkout']['url'] ?? null,
                currency: $data['currency'],
                totalAmount: $data['totalAmount'],
                balanceAmount: $data['balanceAmount'],
                suppliedAmount: $data['suppliedAmount'],
                vat: $data['vat'],
                taxFreeAmount: $data['taxFreeAmount'],
                taxExemptionAmount: $data['taxExemptionAmount'],
                method: $data['method'],
                responseBody: $request->collect()->toJson(),
                responseStatus: 200,
                cancels: $cancels,
            );
        } catch (Exception $exception) {
            Log::debug($exception);
            throw new \RuntimeException("TossPaymentResponseDTO::createFromWebHook", 0, $exception);
        }
    }
}
