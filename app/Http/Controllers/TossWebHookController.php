<?php

namespace App\Http\Controllers;

use App\Enums\MemberPaymentStatusEnum;
use App\Http\Requests\TossWebHookRequest;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TossWebHookController extends Controller
{
    public function __construct(
        protected MemberPaymentServiceInterface $memberPaymentController,
    ) {}

    public function index(TossWebHookRequest $request): JsonResponse
    {
        // 해당하는 결제 정보 가져오기
        $payment = $this->memberPaymentController->findByKey($request->validated('data')['orderId']);
        if (! $payment) {
            return response()->json([
                'message' => "해당하는 결제 내역이 없습니다. :{$request->validated('data')['orderId']}",
            ], Response::HTTP_NOT_FOUND);
        }

        $DTO = $request->toDTO();
        if ($payment->state === MemberPaymentStatusEnum::Done && $DTO->status === 'DONE') {
            return response()->json([
                'message' => '이미 처리된 결제입니다.',
            ], Response::HTTP_CONFLICT);
        }

        try {
            $this->memberPaymentController->process($payment, $DTO);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([
                'message' => 'Server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // 응답
        return response()->json([
            'message' => 'Ok',
        ]);
    }
}
