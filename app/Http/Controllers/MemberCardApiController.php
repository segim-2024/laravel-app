<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMemberCardApiRequest;
use App\Http\Resources\AdminMemberCardResource;
use App\Services\Interfaces\MemberCardServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemberCardApiController extends Controller
{
    public function __construct(
        protected MemberCardServiceInterface $service
    ) {}

    public function index(GetMemberCardApiRequest $request): AnonymousResourceCollection
    {
        $list = $this->service->getAllMemberCards($request->toDTO());
        return AdminMemberCardResource::collection($list);
    }
}
