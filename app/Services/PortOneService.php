<?php

namespace App\Services;

use App\DTOs\PaymentCancelDTO;
use App\DTOs\PortOneBillingPaymentResponseDTO;
use App\DTOs\PortOneGetBillingKeyResponseDTO;
use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\PortOneBillingPaymentException;
use App\Exceptions\PortOneGetBillingKeyException;
use App\Exceptions\PortOneGetPaymentException;
use App\Models\MemberPayment;
use App\Services\Interfaces\PortOneServiceInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortOneService implements PortOneServiceInterface {

    /**
     * @inheritDoc
     */
    public function getBillingKey(string $billingKey): PortOneGetBillingKeyResponseDTO
    {
        $response = Http::portone()
            ->get('/billing-keys/'.$billingKey);

        Log::info('Get Billing key', $response->json());
        if ($response->successful()) {
            if (! $response->json('billingKey') || ! $response->json('methods')) {
                throw new PortOneGetBillingKeyException("빌링키 정보 조회 이상 : {$response->body()}", $response->status());
            }

            return PortOneGetBillingKeyResponseDTO::createFromResponse($response);
        }

        throw new PortOneGetBillingKeyException("빌링키 정보 조회 실패 : {$response->body()}", $response->status());
    }

    /**
     * @inheritDoc
     */
    public function requestPaymentByBillingKey(string $billingKey, MemberPayment $payment): PortOneBillingPaymentResponseDTO
    {
        // 결제 요청 파라미터
        $params = [
            'billingKey' => $billingKey,
            'orderName' => $payment->productable->name,
            'amount' => [
                'total' => $payment->productable->price,
                'taxFree' => $payment->productable->price,
                'tax' => 0,
            ],
            'customer' => [
                'customer_name' => [
                    'full_name' => $payment->member->mb_nick."-".$payment->member->mb_name
                ]
            ],
            'currency' => 'KRW',
            'noticeUrls' => [
                Config::get('services.portone.v2.web_hook_url')
            ]
        ];

        // 이메일이 존재하지 않는 경우가 있어 옵셔널하게 입력
        if ($payment->member->mb_email) {
            $params['customer']['email'] = $payment->member->mb_email;
        }

        $response = Http::portone()
            ->post('/payments/'.$payment->payment_id.'/billing-key', $params);

        Log::info('Request to billing key payment');
        if ($response->successful()) {
            if (! $response->json('payment')) {
                throw new PortOneBillingPaymentException("빌링키 결제 요청 이상 : {$response->body()}", $response->status());
            }

            return PortOneBillingPaymentResponseDTO::createFromResponse($response);
        }

        throw new PortOneBillingPaymentException("빌링키 결제 요청 실패 : {$response->body()}", $response->status());
    }

    /**
     * @inheritDoc
     */
    public function deleteBillingKey(string $billingKey): bool
    {
        $response = Http::portone()
            ->delete('/billing-keys/'.$billingKey);

        if ($response->successful()) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getPaymentDetail(MemberPayment $payment): PortOneGetPaymentResponseDTO
    {
        $response = Http::portone()
            ->get('/payments/'.$payment->payment_id);

        Log::info('Get Payment');
        Log::info($response->body());
        if ($response->successful()) {
            $data = $response->object();
            if (! $data || ! isset($data->status)) {
                throw new PortOneGetPaymentException("결제 정보 조회 이상 : {$response->body()}", $response->status());
            }

            return PortOneGetPaymentResponseDTO::fromResponse($response);
        }

        throw new PortOneGetPaymentException("결제 정보 조회 실패 : {$response->body()}", $response->status());
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentCancelDTO $DTO): bool
    {
        $response = Http::portone()
            ->post('/payments/'.$DTO->payment->payment_id.'/cancel', [
                'amount' => $DTO->amount,
                'reason' => $DTO->reason
            ]);

        if ($response->successful()) {
            return true;
        }

        return false;
    }
}
