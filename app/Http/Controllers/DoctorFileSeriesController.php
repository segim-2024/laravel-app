<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDoctorFileSeriesRequest;
use App\Services\Interfaces\DoctorFileSeriesServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileSeriesController extends Controller
{
    public function __construct(
        protected DoctorFileSeriesServiceInterface $service
    ) {}

    public function destroy(DeleteDoctorFileSeriesRequest $request): JsonResponse
    {
        $this->service->delete($request->series);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
