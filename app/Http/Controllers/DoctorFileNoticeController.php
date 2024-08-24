<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorFileNoticeRequest;
use App\Http\Requests\DeleteDoctorFileNoticeRequest;
use App\Http\Requests\GetListDoctorFileNoticeRequest;
use App\Http\Requests\UpdateDoctorFileNoticeRequest;
use App\Http\Resources\DoctorFileNoticeResource;
use App\Services\Interfaces\DoctorFileNoticeServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorFileNoticeController extends Controller
{
    public function __construct(
        protected DoctorFileNoticeServiceInterface $service
    ) {}

    public function index(GetListDoctorFileNoticeRequest $request): JsonResponse
    {
        $notices = $this->service->getList($request->isWhale);
        return DoctorFileNoticeResource::collection($notices)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(CreateDoctorFileNoticeRequest $request): JsonResponse
    {
        $notice = $this->service->create($request->toDTO());
        return DoctorFileNoticeResource::make($notice)
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateDoctorFileNoticeRequest $request): JsonResponse
    {
        $notice = $this->service->update($request->toDTO());
        return DoctorFileNoticeResource::make($notice)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteDoctorFileNoticeRequest $request): JsonResponse
    {
        $this->service->delete($request->notice);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
