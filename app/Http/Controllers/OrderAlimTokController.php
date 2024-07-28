<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlimTokSendRequest;
use App\Http\Requests\ShipmentTrackAlimTokSendRequest;
use App\Services\Interfaces\OrderAlimTokServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderAlimTokController extends Controller
{
    public function __construct(
        protected OrderAlimTokServiceInterface $service
    ) {}

    public function payment(AlimTokSendRequest $request): JsonResponse
    {
        try {
            $this->service->payment($request->toDTO());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function depositGuidance(AlimTokSendRequest $request): JsonResponse
    {
        try {
            $this->service->depositGuidance($request->toDTO());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function shipmentTrack(ShipmentTrackAlimTokSendRequest $request): JsonResponse
    {
        try {
            $this->service->shipmentTrack($request->toDTO());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
