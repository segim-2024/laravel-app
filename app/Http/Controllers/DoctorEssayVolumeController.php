<?php

namespace App\Http\Controllers;

use App\Exceptions\DoctorEssayVolumeNotFoundException;
use App\Http\Requests\DeleteDoctorEssayVolumeRequest;
use App\Http\Requests\UpdateDoctorEssayVolumeDescriptionRequest;
use App\Http\Requests\UpdateDoctorEssayVolumeIsPublishedRequest;
use App\Http\Requests\UpdateDoctorEssayVolumePosterRequest;
use App\Http\Requests\UpdateDoctorEssayVolumeUrlRequest;
use App\Http\Resources\DoctorEssayVolumeResource;
use App\Services\Interfaces\DoctorEssayVolumeServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DoctorEssayVolumeController extends Controller
{
    public function __construct(
        protected DoctorEssayVolumeServiceInterface $service
    ) {}

    /**
     * 논술 박사 볼륨 상세
     *
     * @param Request $request
     * @param $uuid
     * @return DoctorEssayVolumeResource|JsonResponse
     */
    public function show(Request $request, $uuid): DoctorEssayVolumeResource|JsonResponse
    {
        $volume = $this->service->find($uuid);
        if (! $volume) {
            return response()->json(['message' => '해당 볼륨이 존재하지 않습니다.'], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayVolumeResource::make($volume);
    }

    /**
     * 논술 박사 볼륨 포스터 수정
     *
     * @param UpdateDoctorEssayVolumePosterRequest $request
     * @return DoctorEssayVolumeResource|JsonResponse
     */
    public function updatePoster(UpdateDoctorEssayVolumePosterRequest $request): DoctorEssayVolumeResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            $volume = $this->service->updatePoster($request->toDTO());
            DB::commit();
        } catch (DoctorEssayVolumeNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DoctorEssayVolumeResource::make($volume);
    }

    /**
     * 논술 박사 볼륨 설명 수정
     *
     * @param UpdateDoctorEssayVolumeDescriptionRequest $request
     * @return DoctorEssayVolumeResource|JsonResponse
     */
    public function updateDescription(UpdateDoctorEssayVolumeDescriptionRequest $request): DoctorEssayVolumeResource|JsonResponse
    {
        try {
            $volume = $this->service->updateDescription($request->toDTO());
        } catch (DoctorEssayVolumeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayVolumeResource::make($volume);
    }

    /**
     * 논술 박사 볼륨 공개 여부 수정
     *
     * @param UpdateDoctorEssayVolumeIsPublishedRequest $request
     * @return DoctorEssayVolumeResource|JsonResponse
     */
    public function updateIsPublished(UpdateDoctorEssayVolumeIsPublishedRequest $request): DoctorEssayVolumeResource|JsonResponse
    {
        try {
            $volume = $this->service->updateIsPublished($request->toDTO());
        } catch (DoctorEssayVolumeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayVolumeResource::make($volume);
    }

    /**
     * 논술 박사 볼륨 URL 수정
     *
     * @param UpdateDoctorEssayVolumeUrlRequest $request
     * @return DoctorEssayVolumeResource|JsonResponse
     */
    public function updateUrl(UpdateDoctorEssayVolumeUrlRequest $request): DoctorEssayVolumeResource|JsonResponse
    {
        try {
            $volume = $this->service->updateUrl($request->toDTO());
        } catch (DoctorEssayVolumeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayVolumeResource::make($volume);
    }

    /**
     * 논술 박사 볼륨 삭제
     *
     * @param DeleteDoctorEssayVolumeRequest $request
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy(DeleteDoctorEssayVolumeRequest $request, $uuid): JsonResponse
    {
        $this->service->delete($uuid);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
