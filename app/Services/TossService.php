<?php

namespace App\Services;

use App\DTOs\CreateMemberCardResponseDTO;
use App\DTOs\PaymentCancelDTO;
use App\DTOs\RequestBillingPaymentFailedResponseDTO;
use App\DTOs\TossPaymentResponseDTO;
use App\Models\MemberPayment;
use App\Models\MemberSubscribeProduct;
use App\Services\Interfaces\TossServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TossService implements TossServiceInterface {
    /**
     * @inheritDoc
     */
    public function createBillingKey(string $customerKey, string $authKey): CreateMemberCardResponseDTO
    {
        $response = Http::toss()
            ->post("/billing/authorizations/issue",
                [
                    'customerKey' => $customerKey,
                    'authKey' => $authKey,
                ]
            );

        Log::info($response->body());
        return CreateMemberCardResponseDTO::createFromResponse($response);
    }

    /**
     * @inheritDoc
     */
    public function requestBillingPayment(MemberPayment $payment, MemberSubscribeProduct $subscribeProduct): RequestBillingPaymentFailedResponseDTO|TossPaymentResponseDTO
    {
        $response = Http::toss()
            ->post("/billing/{$subscribeProduct->card->key}", [
                'customerKey' => $subscribeProduct->member->toss_customer_key,
                'amount' => $subscribeProduct->product->price,
                'orderId' => $payment->payment_id,
                'orderName' => $payment->title,
                'customerEmail' => $payment->member->mb_email,
                'customerName' => $payment->member->mb_name,
                'taxFreeAmount' => 0,
            ]);

        if ($response->failed()) {
            return RequestBillingPaymentFailedResponseDTO::createFromResponse($response);
        }

        return TossPaymentResponseDTO::createFromResponse($response);
    }

    /**
     * @inheritDoc
     */
    public function requestCancel(PaymentCancelDTO $DTO): TossPaymentResponseDTO
    {
        $response = Http::toss()
            ->post(
                "/payments/{$DTO->payment->tx_id}/cancel",
                [
                    'paymentKey' => $DTO->payment->tx_id,
                    'cancelAmount' => $DTO->amount,
                    'cancelReason' => $DTO->reason,
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException($response->body(), $response->status());
        }

        return TossPaymentResponseDTO::createFromResponse($response);
    }
}
