<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorFileLessonMaterialRequest;
use App\Http\Requests\DeleteDoctorFileLessonMaterialRequest;
use App\Http\Requests\UpdateDoctorFileLessonMaterialRequest;
use App\Http\Resources\DoctorFileLessonMaterialResource;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileLessonMaterialController extends Controller
{
    public function __construct(
        protected DoctorFileLessonMaterialServiceInterface $service,
    ) {}

    public function store(CreateDoctorFileLessonMaterialRequest $request): JsonResponse
    {
        $material = $this->service->create($request->toDTO());
        return DoctorFileLessonMaterialResource::make($material)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateDoctorFileLessonMaterialRequest $request): JsonResponse
    {
        $material = $this->service->update($request->toDTO());
        return DoctorFileLessonMaterialResource::make($material)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteDoctorFileLessonMaterialRequest $request): JsonResponse
    {
        $this->service->delete($request->material);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
