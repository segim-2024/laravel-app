<?php

namespace App\Services;

use App\Enums\WhaleLearningPlatformEnum;
use App\Services\Interfaces\WhaleLearningDownloadServiceInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WhaleLearningDownloadService implements WhaleLearningDownloadServiceInterface
{
    public function getPresignedUrl(WhaleLearningPlatformEnum $platform): string
    {
        $path = $platform->getS3Path();

        if (! Storage::disk('s3')->exists($path)) {
            throw new NotFoundHttpException('파일을 찾을 수 없습니다.');
        }

        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));
    }
}
