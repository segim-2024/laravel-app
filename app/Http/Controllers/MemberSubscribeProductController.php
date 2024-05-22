<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnsubscribeProductRequest;
use App\Http\Requests\UpdateActivateMemberSubscribeProductRequest;
use App\Http\Requests\UpsertMemberSubscribeProductRequest;
use App\Http\Resources\MemberSubscribeProductResource;
use App\Http\Resources\ProductResource;
use App\Models\MemberCard;
use App\Models\Product;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MemberSubscribeProductController extends Controller
{
    public function __construct(
        protected ProductServiceInterface $productService,
        protected MemberCardServiceInterface $cardService,
        protected MemberSubscribeProductServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        /** @var Collection|Product[] $products */
        $products = $this->productService->getList($request->user());

        /** @var Collection|MemberCard[] $products */
        $cards = $this->cardService->getList($request->user());

        return view('products.product_list', [
            'products' => $products,
            'cards' => $cards
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $products = $this->productService->getList($request->user());
        return DataTables::of($products)->make();
    }

    public function subscribe(UpsertMemberSubscribeProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $products = $this->service->subscribe($request->toDTO());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ProductResource::collection($products)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateActivate(UpdateActivateMemberSubscribeProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $subscribe = $this->service->updateActivate($request->toDTO());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Failed to update activation status'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (new MemberSubscribeProductResource($subscribe))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function unsubscribe(UnsubscribeProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->service->unsubscribe($request->toDTO());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json(['message' => 'Failed to unsubscribe'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
