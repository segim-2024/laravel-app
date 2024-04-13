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
        $start = $request->input('start') ? Carbon::parse($request->input('start'))->startOfDay() : null;
        $end = $request->input('end') ? Carbon::parse($request->input('end'))->endOfDay() : null;
        $payments = $this->service->getList(new GetMemberPaymentListDTO(
            member: $request->user(),
            start: $start,
            end: $end,
            keyword: $request->input('keyword'),
        ));

        return DataTables::of($payments)->make();
    }
}
