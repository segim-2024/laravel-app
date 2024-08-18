<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorFileLessonRequest;
use App\Http\Requests\GetShowDoctorFileLessonRequest;
use App\Http\Resources\DoctorFileLessonResource;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileLessonController extends Controller
{
    public function __construct(
        protected DoctorFileLessonServiceInterface $service
    ) {}

    public function show(GetShowDoctorFileLessonRequest $request): JsonResponse
    {
        return DoctorFileLessonResource::make($request->lesson)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteDoctorFileLessonRequest $request): JsonResponse
    {
        $this->service->delete($request->lesson);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
