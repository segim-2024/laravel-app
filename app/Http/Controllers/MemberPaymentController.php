<?php

namespace App\Http\Controllers;

use App\DTOs\GetMemberPaymentListDTO;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class MemberPaymentController extends Controller
{
    public function __construct(
        protected MemberPaymentServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        $totalAmount = $this->service->getTotalAmount($request->user());
        $totalCount = $this->service->getTotalPaymentCount($request->user());
        return view('payments.payment_list', [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $start = $request->input('periodStart') ? Carbon::parse($request->input('periodStart'))->startOfDay() : null;
        $end = $request->input('periodEnd') ? Carbon::parse($request->input('periodEnd'))->endOfDay() : null;

        return $this->service->getList(new GetMemberPaymentListDTO(
            member: $request->user(),
            start: $start,
            end: $end,
            keyword: $request->input('keyword'),
        ));
    }
}
