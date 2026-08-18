<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateS3PresignedUrlRequest;
use App\Http\Requests\DeleteS3FileRequest;
use App\Services\Interfaces\S3FileServiceInterface;
use Illuminate\Http\JsonResponse;

class S3FileController extends Controller
{
    public function __construct(
        protected S3FileServiceInterface $service
    ) {}

    /**
     * 업로드용 presigned PUT URL 발급
     */
    public function presignedUrl(CreateS3PresignedUrlRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->createUploadUrl($request->getDTO())->toArray()
        );
    }

    /**
     * S3 객체 삭제
     */
    public function destroy(DeleteS3FileRequest $request): JsonResponse
    {
        $this->service->delete($request->getDTO());

        return response()->json(['message' => '삭제되었습니다.']);
    }
}
