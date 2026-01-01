<?php

namespace App\Services\Interfaces;

use App\Enums\WhaleLearningPlatformEnum;

interface WhaleLearningDownloadServiceInterface
{
    public function getPresignedUrl(WhaleLearningPlatformEnum $platform): string;
}
