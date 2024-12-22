<?php

namespace App\Http\Controllers;

use App\Exceptions\DoctorEssayLessonNotFoundExeption;
use App\Exceptions\DoctorEssayMaterialNotFoundExeption;
use App\Http\Requests\CreateDoctorEssayMaterialRequest;
use App\Http\Requests\DeleteDoctorEssayMaterialRequest;
use App\Http\Requests\UpdateDoctorEssayMaterialRequest;
use App\Http\Resources\DoctorEssayMaterialResource;
use App\Services\Interfaces\DoctorEssayMaterialServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorEssayMaterialController extends Controller
{
    public function __construct(
        protected DoctorEssayMaterialServiceInterface $service
    ) {}

    /**
     * 자료 박사 파일 생성
     *
     * @param CreateDoctorEssayMaterialRequest $request
     * @return DoctorEssayMaterialResource|JsonResponse
     */
    public function store(CreateDoctorEssayMaterialRequest $request)
    {
        try {
            $material = $this->service->create($request->toDTO());
        } catch (DoctorEssayLessonNotFoundExeption $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayMaterialResource::make($material);
    }

    /**
     * 자료 박사 파일 수정
     *
     * @param UpdateDoctorEssayMaterialRequest $request
     * @return DoctorEssayMaterialResource|JsonResponse
     */
    public function update(UpdateDoctorEssayMaterialRequest $request)
    {
        try {
            $material = $this->service->update($request->toDTO());
        } catch (DoctorEssayMaterialNotFoundExeption $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayMaterialResource::make($material);
    }

    /**
     * 논술 박사 자료 삭제
     *
     * @param DeleteDoctorEssayMaterialRequest $request
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy(DeleteDoctorEssayMaterialRequest $request, $uuid): JsonResponse
    {
        $this->service->delete($uuid);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
