<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetWhaleLearningDownloadUrlRequest;
use App\Services\Interfaces\WhaleLearningDownloadServiceInterface;
use Illuminate\Http\JsonResponse;

class WhaleLearningDownloadController extends Controller
{
    public function __construct(
        protected WhaleLearningDownloadServiceInterface $service
    ) {}

    public function getUrl(GetWhaleLearningDownloadUrlRequest $request): JsonResponse
    {
        $url = $this->service->getPresignedUrl($request->getPlatform());

        return response()->json(['url' => $url]);
    }
}
