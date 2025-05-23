<?php

namespace App\Http\Controllers;

use App\DTOs\OrderSegimTicketMinusDTO;
use App\DTOs\OrderSegimTicketPlusDTO;
use App\Enums\SegimTicketMinusTypeEnum;
use App\Http\Requests\OrderSegimTicketMinusRequest;
use App\Http\Requests\OrderSegimTicketPlusRequest;
use App\Services\Interfaces\OrderSegimTicketServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderSegimTicketController extends Controller
{
    public function __construct(
        protected OrderSegimTicketServiceInterface $service
    ) {}

    public function plus(OrderSegimTicketPlusRequest $request): JsonResponse
    {
        Log::info($request->all());
        $this->service->callPlusJob(new OrderSegimTicketPlusDTO(
            ctIds: collect($request->validated('ct_ids'))
        ));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function minus(OrderSegimTicketMinusRequest $request): JsonResponse
    {
        Log::info($request->all());
        $this->service->callMinusJob(new OrderSegimTicketMinusDTO(
            type: SegimTicketMinusTypeEnum::from($request->validated('type')),
            ids: collect($request->validated('ids'))
        ));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
