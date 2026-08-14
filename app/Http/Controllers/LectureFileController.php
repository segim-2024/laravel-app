<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLecturePresignedUrlRequest;
use App\Http\Requests\DeleteLectureFileRequest;
use App\Services\Interfaces\LectureFileServiceInterface;
use Illuminate\Http\JsonResponse;

class LectureFileController extends Controller
{
    public function __construct(
        protected LectureFileServiceInterface $service
    ) {}

    /**
     * 강의 파일 업로드용 presigned PUT URL 발급
     */
    public function presignedUrl(CreateLecturePresignedUrlRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->createUploadUrl($request->getDTO())->toArray()
        );
    }

    /**
     * 강의 파일 삭제
     */
    public function destroy(DeleteLectureFileRequest $request): JsonResponse
    {
        $this->service->delete($request->getDTO());

        return response()->json(['message' => '삭제되었습니다.']);
    }
}
