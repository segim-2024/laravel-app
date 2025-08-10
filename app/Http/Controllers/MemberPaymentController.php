<?php

namespace App\Http\Controllers;

use App\DTOs\GetMemberPaymentListDTO;
use App\Exceptions\PaymentIsNotFailedException;
use App\Exceptions\PortOneBillingPaymentException;
use App\Http\Requests\PaymentCancelRequest;
use App\Http\Requests\PaymentRetryRequest;
use App\Http\Resources\MemberPaymentResource;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MemberPaymentController extends Controller
{
    public function __construct(
        protected MemberPaymentServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        $totalAmount = $this->service->getTotalAmount($request->user());
        $totalCount = $this->service->getTotalPaymentCount($request->user());
        return view('payments.payment_list', [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $start = $request->input('periodStart') ? Carbon::parse($request->input('periodStart'))->startOfDay() : null;
        $end = $request->input('periodEnd') ? Carbon::parse($request->input('periodEnd'))->endOfDay() : null;

        return $this->service->getList(new GetMemberPaymentListDTO(
            member: $request->user(),
            start: $start,
            end: $end,
            keyword: $request->input('keyword'),
        ));
    }

    public function retry(PaymentRetryRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payment = $this->service->retry($request->toDTO());
            DB::commit();
        } catch (PortOneBillingPaymentException $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new MemberPaymentResource($payment))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function cancel(PaymentCancelRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payment = $this->service->cancel($request->toDTO());
            DB::commit();
        } catch (PortOneBillingPaymentException $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new MemberPaymentResource($payment))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * 실패한 결제 정보 삭제
     *
     * @param Request $request
     * @param string $paymentId
     * @return JsonResponse
     */
    public function destroyFailedPayment(Request $request, string $paymentId): JsonResponse
    {
        try {
            $this->service->deleteFailedPayment($paymentId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (PaymentIsNotFailedException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
