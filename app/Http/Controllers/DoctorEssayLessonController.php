<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorEssayLessonRequest;
use App\Http\Resources\DoctorEssayLessonResource;
use App\Services\Interfaces\DoctorEssayLessonServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorEssayLessonController extends Controller
{
    public function __construct(
        protected DoctorEssayLessonServiceInterface $service
    ) {}

    /**
     * 논술 박사 레쓴 상세
     *
     * @param Request $request
     * @param $uuid
     * @return DoctorEssayLessonResource|JsonResponse
     */
    public function show(Request $request, $uuid)
    {
        $lesson = $this->service->find($uuid);
        if (! $lesson) {
            return response()->json(['message' => '해당 레쓴이 존재하지 않습니다.'], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayLessonResource::make($lesson);
    }

    /**
     * 논술 박사 레쓴 삭제
     *
     * @param DeleteDoctorEssayLessonRequest $request
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy(DeleteDoctorEssayLessonRequest $request, $uuid): JsonResponse
    {
        $this->service->delete($uuid);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
