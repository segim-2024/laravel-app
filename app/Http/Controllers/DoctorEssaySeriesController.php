<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorEssaySeriesRequest;
use App\Http\Requests\GetListDoctorEssaySeriesRequest;
use App\Http\Resources\DoctorEssaySeriesResource;
use App\Services\Interfaces\DoctorEssaySeriesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class DoctorEssaySeriesController extends Controller
{
    public function __construct(
        protected DoctorEssaySeriesServiceInterface $service
    ) {}

    /**
     * 논술 박사 시리즈 리스트
     *
     * @param GetListDoctorEssaySeriesRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetListDoctorEssaySeriesRequest $request): AnonymousResourceCollection
    {
        $list = $this->service->getList($request->isWhale());
        return DoctorEssaySeriesResource::collection($list);
    }

    /**
     * 논술 박사 시리즈 삭제
     *
     * @param DeleteDoctorEssaySeriesRequest $request
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy(DeleteDoctorEssaySeriesRequest $request, $uuid): JsonResponse
    {
        $this->service->delete($uuid);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
