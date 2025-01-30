<?php

namespace App\Http\Controllers;

use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Http\Requests\CreateMemberCashOrderRequest;
use App\Http\Requests\ECashManualChargeRequest;
use App\Http\Requests\ECashManualSpendRequest;
use App\Http\Requests\MemberCashManualChargeRequest;
use App\Http\Requests\MemberCashManualSpendRequest;
use App\Http\Resources\MemberCashResource;
use App\Services\Interfaces\MemberCashServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MemberCashController extends Controller
{
    public function __construct(
        protected MemberCashServiceInterface $service
    ) {}

    public function order(CreateMemberCashOrderRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $memberCash = $this->service->spend($request->toDTO());
            DB::commit();
        } catch (MemberCashNotEnoughToSpendException $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new MemberCashResource($memberCash))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param MemberCashManualSpendRequest $request
     * @return MemberCashResource|JsonResponse
     */
    public function manualSpend(MemberCashManualSpendRequest $request)
    {
        DB::beginTransaction();
        try {
            $memberCash = $this->service->spend($request->toDTO());
            DB::commit();
        } catch (MemberCashNotEnoughToSpendException $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return MemberCashResource::make($memberCash);
    }

    /**
     * @param MemberCashManualChargeRequest $request
     * @return MemberCashResource|JsonResponse
     */
    public function manualCharge(MemberCashManualChargeRequest $request)
    {
        DB::beginTransaction();
        try {
            $memberCash = $this->service->charge($request->toDTO());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return MemberCashResource::make($memberCash);
    }

    /**
     * @param ECashManualChargeRequest $request
     * @return MemberCashResource
     */
    public function charge(ECashManualChargeRequest $request): MemberCashResource
    {
        $memberCash = $this->service->charge($request->toDTO());
        return MemberCashResource::make($memberCash);
    }

    /**
     * @param ECashManualSpendRequest $request
     * @return MemberCashResource|JsonResponse
     */
    public function spend(ECashManualSpendRequest $request)
    {
        try {
            $memberCash = $this->service->spend($request->toDTO());
        } catch (MemberCashNotEnoughToSpendException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return MemberCashResource::make($memberCash);
    }
}
