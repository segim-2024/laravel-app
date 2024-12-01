<?php

namespace App\Http\Controllers;

use App\Exceptions\CardForbbidenException;
use App\Exceptions\LibraryProductSubscribeConflictException;
use App\Exceptions\LibraryProductSubscribeForbbidenException;
use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Http\Requests\LibraryProductSubscribeRequest;
use App\Http\Requests\LibraryProductUnsubscribeRequest;
use App\Http\Requests\UpdateLibraryProductSubscribeCardRequest;
use App\Http\Resources\LibraryProductSubscribeResource;
use App\Models\LibraryProduct;
use App\Models\MemberCard;
use App\Services\Interfaces\LibraryProductServiceInterface;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LibraryProductSubscribeController extends Controller
{
    public function __construct(
        protected LibraryProductServiceInterface $productService,
        protected MemberCardServiceInterface $cardService,
        protected LibraryProductSubscribeServiceInterface $service
    ) {}

    /**
     * 구독 상품 관리 페이지
     *
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        /** @var Collection|LibraryProduct[] $products */
        $products = $this->productService->getListForMember();

        /** @var Collection|MemberCard[] $cards */
        $cards = $this->cardService->getList($request->user());

        return view('products.library_product_list', [
            'products' => $products,
            'cards' => $cards
        ]);
    }

    /**
     * 구독 상품 관리 페이지 - 구독 신청
     *
     * @param LibraryProductSubscribeRequest $request
     * @return JsonResponse|LibraryProductSubscribeResource
     */
    public function subscribe(LibraryProductSubscribeRequest $request): JsonResponse|LibraryProductSubscribeResource
    {
        try {
            $subscribe = $this->service->subscribe($request->toDTO());
        } catch (LibraryProductSubscribeConflictException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => '구독 중 오류가 발생했습니다.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return LibraryProductSubscribeResource::make($subscribe)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * 구독 상품 관리 페이지 - 구독 해지
     *
     * @param LibraryProductUnsubscribeRequest $request
     * @return JsonResponse|LibraryProductSubscribeResource
     */
    public function unsubscribe(LibraryProductUnsubscribeRequest $request): JsonResponse|LibraryProductSubscribeResource
    {
        try {
            $subscribe = $this->service->unsubscribe($request->toDTO());
        } catch (LibraryProductSubscribeNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return LibraryProductSubscribeResource::make($subscribe)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * 구독 상품 관리 페이지 - 카드 변경
     *
     * @param UpdateLibraryProductSubscribeCardRequest $request
     * @return JsonResponse|LibraryProductSubscribeResource
     */
    public function updateCard(UpdateLibraryProductSubscribeCardRequest $request): JsonResponse|LibraryProductSubscribeResource
    {
        try {
            $subscribe = $this->service->updateCard($request->toDTO());
        } catch (CardForbbidenException|LibraryProductSubscribeForbbidenException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        return LibraryProductSubscribeResource::make($subscribe)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * 구독 상품 관리 페이지 - 구독 가능 여부 체크
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkCanSubscribe(Request $request): JsonResponse
    {
        $isCanSubscribe = $this->service->isCanSubscribe($request->user()->mb_id);
        if ($isCanSubscribe) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json([
            'message' => '이미 구독/미납 중인 라이브러리 상품이 있습니다.'
        ], Response::HTTP_CONFLICT);
    }
}
