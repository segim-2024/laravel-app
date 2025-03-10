<?php

namespace App\Http\Controllers;

use App\Exceptions\LibraryProductSubscribeNotFoundException;
use App\Http\Requests\LibraryProductUnsubscribeByAdminRequest;
use App\Http\Resources\LibraryProductSubscribeResource;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class LibraryProductSubscribeApiController extends Controller
{
    public function __construct(
        protected LibraryProductSubscribeServiceInterface $service
    ) {}

    public function unsubscribe(LibraryProductUnsubscribeByAdminRequest $request)
    {
        try {
            $subscribe = $this->service->unsubscribe($request->toDTO());
        } catch (LibraryProductSubscribeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return LibraryProductSubscribeResource::make($subscribe);
    }
}
