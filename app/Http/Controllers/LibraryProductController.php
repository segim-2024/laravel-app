<?php

namespace App\Http\Controllers;

use App\Exceptions\LibraryProductNotFoundException;
use App\Http\Requests\CreateLibraryProductRequest;
use App\Http\Requests\UpdateLibraryProductRequest;
use App\Http\Resources\LibraryProductResource;
use App\Services\Interfaces\LibraryProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LibraryProductController extends Controller
{
    public function __construct(
        protected LibraryProductServiceInterface $service,
    ) {}

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return LibraryProductResource::collection($this->service->getList());
    }

    /**
     * @param CreateLibraryProductRequest $request
     * @return JsonResponse
     */
    public function store(CreateLibraryProductRequest $request): JsonResponse
    {
        $product = $this->service->create($request->toDTO());
        return LibraryProductResource::make($product)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param UpdateLibraryProductRequest $request
     * @return LibraryProductResource|JsonResponse
     */
    public function update(UpdateLibraryProductRequest $request)
    {
        try {
            $product = $this->service->update($request->toDTO());
        } catch (LibraryProductNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return LibraryProductResource::make($product);
    }
}
