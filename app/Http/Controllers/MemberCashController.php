<?php

namespace App\Http\Controllers;

use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Exceptions\MemberCashRepositoryFactoryException;
use App\Exceptions\MemberCashTransactionRepositoryFactoryException;
use App\Http\Requests\CreateMemberCashOrderRequest;
use App\Http\Requests\ECashManualChargeRequest;
use App\Http\Requests\ECashManualSpendRequest;
use App\Http\Requests\ECashOrderRequest;
use App\Http\Requests\GetECashHistoryRequest;
use App\Http\Requests\MemberCashManualChargeRequest;
use App\Http\Requests\MemberCashManualSpendRequest;
use App\Http\Resources\ECashHistoryExcelResource;
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

    /**
     * @param GetECashHistoryRequest $request
     * @return ECashHistoryExcelResource|JsonResponse
     */
    public function excel(GetECashHistoryRequest $request)
    {
        try {
            $list = $this->service->getHistoryExcel($request->toDTO());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ECashHistoryExcelResource::make($list);
    }

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
     * @return MemberCashResource|JsonResponse
     */
    public function charge(ECashManualChargeRequest $request)
    {
        try {
            $memberCash = $this->service->charge($request->toDTO());
        } catch (MemberCashTransactionRepositoryFactoryException|MemberCashRepositoryFactoryException $e) {
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
        } catch (MemberCashTransactionRepositoryFactoryException|MemberCashRepositoryFactoryException $e) {
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return MemberCashResource::make($memberCash);
    }

    public function order2(ECashOrderRequest $request)
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
}
