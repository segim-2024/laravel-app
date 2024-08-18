<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorFileVolumeRequest;
use App\Http\Requests\GetShowDoctorFileVolumeRequest;
use App\Http\Requests\UpdateDoctorFileVolumeDescriptionRequest;
use App\Http\Requests\UpdateDoctorFileVolumeIsPublishedRequest;
use App\Http\Requests\UpdateDoctorFileVolumePosterRequest;
use App\Http\Resources\DoctorFileVolumeResource;
use App\Services\Interfaces\DoctorFileVolumeServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileVolumeController extends Controller
{
    public function __construct(
        protected DoctorFileVolumeServiceInterface $service
    ) {}

    public function show(GetShowDoctorFileVolumeRequest $request): JsonResponse
    {
        return DoctorFileVolumeResource::make($request->volume)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updatePoster(UpdateDoctorFileVolumePosterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $volume = $this->service->updatePoster($request->toDTO());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DoctorFileVolumeResource::make($volume)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateDescription(UpdateDoctorFileVolumeDescriptionRequest $request): JsonResponse
    {
        $volume = $this->service->updateDescription($request->toDTO());
        return DoctorFileVolumeResource::make($volume)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateIsPublished(UpdateDoctorFileVolumeIsPublishedRequest $request): JsonResponse
    {
        $volume = $this->service->updateIsPublished($request->toDTO());
        return DoctorFileVolumeResource::make($volume)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteDoctorFileVolumeRequest $request): JsonResponse
    {
        $this->service->delete($request->volume);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
