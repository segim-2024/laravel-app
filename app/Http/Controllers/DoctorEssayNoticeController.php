<?php

namespace App\Http\Controllers;

use App\Exceptions\DoctorEssayNoticeNotFoundException;
use App\Http\Requests\CreateDoctorEssayNoticeRequest;
use App\Http\Requests\DeleteDoctorEssayNoticeRequest;
use App\Http\Requests\GetListDoctorEssayNoticeRequest;
use App\Http\Requests\UpdateDoctorEssayNoticeRequest;
use App\Http\Resources\DoctorEssayNoticeResource;
use App\Services\Interfaces\DoctorEssayNoticeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class DoctorEssayNoticeController extends Controller
{
    public function __construct(
        protected DoctorEssayNoticeServiceInterface $service
    ) {}

    /**
     * 논술 박사 공지사항 리스트
     *
     * @param GetListDoctorEssayNoticeRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetListDoctorEssayNoticeRequest $request): AnonymousResourceCollection
    {
        $notices = $this->service->getList($request->isWhale());
        return DoctorEssayNoticeResource::collection($notices);
    }

    /**
     * 논술 박사 공지사항 입력
     *
     * @param CreateDoctorEssayNoticeRequest $request
     * @return DoctorEssayNoticeResource
     */
    public function store(CreateDoctorEssayNoticeRequest $request): DoctorEssayNoticeResource
    {
        $notice = $this->service->create($request->toDTO());
        return DoctorEssayNoticeResource::make($notice);
    }

    /**
     * 논술 박사 공지사항 수정
     *
     * @param UpdateDoctorEssayNoticeRequest $request
     * @return DoctorEssayNoticeResource|JsonResponse
     */
    public function update(UpdateDoctorEssayNoticeRequest $request)
    {
        try {
            $notice = $this->service->update($request->toDTO());
        } catch (DoctorEssayNoticeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return DoctorEssayNoticeResource::make($notice);
    }

    /**
     * 논술 박사 공지사항 삭제
     *
     * @param DeleteDoctorEssayNoticeRequest $request
     * @return JsonResponse
     */
    public function destroy(DeleteDoctorEssayNoticeRequest $request): JsonResponse
    {
        $this->service->delete($request->toDTO());
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
