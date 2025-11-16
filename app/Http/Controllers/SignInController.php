<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInAppRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Resources\AccessTokenResource;
use App\Services\Interfaces\MemberServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SignInController extends Controller
{
    public function __construct(
       protected MemberServiceInterface $service
    ) {}


    public function signIn(SignInRequest $request): JsonResponse
    {
        return AccessTokenResource::make($this->service->createToken($request->member))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function signInApp(SignInAppRequest $request): JsonResponse
    {
        return AccessTokenResource::make($this->service->createToken($request->member))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
