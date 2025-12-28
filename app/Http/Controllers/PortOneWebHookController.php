<?php

namespace App\Http\Controllers;

use App\DTOs\PortOneGetPaymentResponseDTO;
use App\Exceptions\PortOneGetPaymentException;
use App\Http\Requests\PortOneWebHookRequest;
use App\Models\Interfaces\PaymentInterface;
use App\Repositories\Factories\MemberPaymentRepositoryFactory;
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
        protected MemberSubscribeProductServiceInterface $subscribeService,
        protected MemberPaymentRepositoryFactory $repositoryFactory
    ) {}

    public function webHook(PortOneWebHookRequest $request): JsonResponse
    {
        $paymentId = $request->validated('payment_id');
        Log::info('============== 웹훅 시작 ==============', [$request->post()]);

        // 1. 먼저 포트원에서 결제 상세 조회 (customData 포함)
        try {
            $portOnePaymentDTO = $this->service->getPaymentDetail($paymentId);
        } catch (PortOneGetPaymentException $exception) {
            Log::error($exception);

            return response()->json(['status' => 'forgery', 'message' => 'Get payment detail failed'], 400);
        } catch (Exception $exception) {
            Log::error($exception);

            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }

        // 2. customData.isWhale로 적절한 Repository 선택하여 DB에서 결제 정보 조회
        $repository = $this->repositoryFactory->createByIsWhale($portOnePaymentDTO->isWhale());
        $payment = $repository->findByKey($paymentId);
        if (! $payment) {
            Log::error('Forgery : Not found');

            return response()->json(['status' => 'forgery', 'message' => 'Not found payment data'], 404);
        }

        // 3. 금액 검증
        if (! $this->verifyAmount($portOnePaymentDTO, $payment)) {
            Log::error('Forgery : Amount mismatch');

            return response()->json(['status' => 'forgery', 'message' => 'The payment is invalid.'], 400);
        }

        // 4. 결제 상태에 따른 처리
        DB::beginTransaction();
        try {
            $this->paymentService->process($payment, $portOnePaymentDTO);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }

        Log::info('============== 웹훅 종료 ==============');

        return response()->json(['status' => $portOnePaymentDTO->status]);
    }

    private function verifyAmount(PortOneGetPaymentResponseDTO $paymentDTO, PaymentInterface $payment): bool
    {
        return $paymentDTO->amount->total === $payment->getAmount();
    }
}
