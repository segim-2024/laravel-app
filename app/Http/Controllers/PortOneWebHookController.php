<?php

namespace App\Http\Controllers;

use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\PaymentAmountVerifyFailedException;
use App\Exceptions\PortOneGetPaymentException;
use App\Http\Requests\PortOneWebHookRequest;
use App\Models\Interfaces\PaymentInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PortOneWebHookController extends Controller
{
    public function __construct(
        protected PortOneServiceInterface $service,
        protected MemberPaymentServiceInterface $paymentService,
        protected MemberSubscribeProductServiceInterface $subscribeService
    ) {}

    public function webHook(PortOneWebHookRequest $request): JsonResponse
    {
        Log::info("============== 웹훅 시작 ==============", [$request->post()]);
        // 결제 데이터 가져오기
        $payment = $this->paymentService->findByKey($request->validated('payment_id'));
        if (! $payment) {
            Log::error('Forgery : Not found');
            return response()->json(['status' => 'forgery', 'message' => 'Not found payment data'], 404);
        }

        // 검증 및 데이터 자장을 위해 포트원 결제 데이터 요청
        try {
            $portOnePaymentDTO = $this->service->getPaymentDetail($payment);
            if (! $this->verifyAmount($portOnePaymentDTO, $payment)) {
                throw new PaymentAmountVerifyFailedException('결제 금액이 일치하지 않아요.', 400);
            }

        } catch (PortOneGetPaymentException $exception) {
            Log::error($exception);
            return response()->json(['status' => 'forgery', 'message' => 'Get payment detail failed'], 400);
        } catch (PaymentAmountVerifyFailedException $exception) {
            Log::error($exception);
            return response()->json(['status' => 'forgery', 'message' => 'The payment is invalid.'], 400);
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }

        // 결제 상태에 따른 처리
        DB::beginTransaction();
        try {
            $this->paymentService->process($payment, $portOnePaymentDTO);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }

        Log::info("============== 웹훅 종료 ==============");
        return response()->json(['status' => $portOnePaymentDTO->status]);
    }

    private function verifyAmount(PortOneGetPaymentResponseDTO $paymentDTO, PaymentInterface $payment): bool
    {
        return $paymentDTO->amount->total === $payment->getAmount();
    }
}
