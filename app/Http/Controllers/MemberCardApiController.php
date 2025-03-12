<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMemberCardApiRequest;
use App\Http\Resources\AdminMemberCardResource;
use App\Services\Interfaces\MemberCardServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class MemberCardApiController extends Controller
{
    public function __construct(
        protected MemberCardServiceInterface $service,
    ) {}

    /**
     * 카드 리스트
     *
     * @param GetMemberCardApiRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetMemberCardApiRequest $request): AnonymousResourceCollection
    {
        $list = $this->service->getAllMemberCards($request->toDTO());
        return AdminMemberCardResource::collection($list);
    }

    /**
     * 카드 삭제
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $card = $this->service->findById($id);
        if (! $card) {
            return response()->json(['message' => '해당하는 카드를 찾을 수 없습니다.'], 404);
        }

        $this->service->delete($card);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
