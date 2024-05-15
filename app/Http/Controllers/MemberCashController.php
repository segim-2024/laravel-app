<?php

namespace App\Http\Controllers;

use App\Exceptions\MemberCashNotEnoughToSpendException;
use App\Http\Requests\CreateMemberCashOrderRequest;
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
        if (! $request->member->cash) {
            $this->service->create($request->member);
        }

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

    public function manualSpend(MemberCashManualSpendRequest $request): JsonResponse
    {
        if (! $request->member->cash) {
            $this->service->create($request->member);
        }

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

    public function manualCharge(MemberCashManualChargeRequest $request): JsonResponse
    {
        if (! $request->member->cash) {
            $this->service->create($request->member);
        }

        DB::beginTransaction();
        try {
            $memberCash = $this->service->charge($request->toDTO());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new MemberCashResource($memberCash))
            ->response()->setStatusCode(Response::HTTP_OK);
    }
}
