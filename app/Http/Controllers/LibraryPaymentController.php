<?php

namespace App\Http\Controllers;

use App\DTOs\GetLibraryPaymentListDTO;
use App\Services\Interfaces\LibraryPaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LibraryPaymentController extends Controller
{
    public function __construct(
        protected LibraryPaymentServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        $totalAmount = $this->service->getTotalAmount($request->user());
        $totalCount = $this->service->getTotalPaymentCount($request->user());
        return view('payments.library_payment_list', [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $start = $request->input('periodStart') ? Carbon::parse($request->input('periodStart'))->startOfDay() : null;
        $end = $request->input('periodEnd') ? Carbon::parse($request->input('periodEnd'))->endOfDay() : null;

        return $this->service->getList(new GetLibraryPaymentListDTO(
            memberId: $request->user()->mb_id,
            start: $start,
            end: $end,
            keyword: $request->input('keyword'),
        ));
    }
}
