<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorFileSeriesRequest;
use App\Http\Requests\GetListDoctorFileSeriesRequest;
use App\Http\Resources\DoctorFileSeriesResource;
use App\Services\Interfaces\DoctorFileSeriesServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileSeriesController extends Controller
{
    public function __construct(
        protected DoctorFileSeriesServiceInterface $service
    ) {}

    public function index(GetListDoctorFileSeriesRequest $request): JsonResponse
    {
        $list = $this->service->getList($request->toDTO());
        return DoctorFileSeriesResource::collection($list)
            ->response()->setStatusCode(Response::HTTP_OK);

    }

    public function destroy(DeleteDoctorFileSeriesRequest $request): JsonResponse
    {
        $this->service->delete($request->series);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
