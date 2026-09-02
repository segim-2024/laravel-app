<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowLibraryMemberRequest;
use App\Http\Resources\LibraryMemberResource;
use App\Services\Interfaces\LibraryMemberServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LibraryMemberController extends Controller
{
    public function __construct(
        protected LibraryMemberServiceInterface $service
    ) {}

    /**
     * 계정/비밀번호로 회원 정보를 조회한다.
     *
     * 회원이 없거나 탈퇴/차단된 경우 204, 비밀번호 불일치는 예외에서 403으로 응답한다.
     */
    public function show(ShowLibraryMemberRequest $request): JsonResponse
    {
        $member = $this->service->find($request->toDTO());

        if (! $member) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return LibraryMemberResource::make($member)
            ->response()
            ->header('Cache-Control', 'no-store, private');
    }
}
