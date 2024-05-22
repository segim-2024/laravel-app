<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateActivateMemberSubscribeProductRequest;
use App\Http\Requests\UpsertMemberSubscribeProductRequest;
use App\Http\Resources\MemberSubscribeProductResource;
use App\Http\Resources\ProductResource;
use App\Models\MemberCard;
use App\Models\Product;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $products = $this->service->subscribe($request->toDTO());
        return ProductResource::collection($products)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateActivate(UpdateActivateMemberSubscribeProductRequest $request): JsonResponse
    {
        $subscribe = $this->service->updateActivate($request->toDTO());
        return (new MemberSubscribeProductResource($subscribe))
            ->response()->setStatusCode(Response::HTTP_OK);
    }
}
