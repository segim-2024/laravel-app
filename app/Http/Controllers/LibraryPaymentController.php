<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Http\Request;

class LibraryPaymentController extends Controller
{
    public function __construct(
        protected MemberPaymentServiceInterface $service
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
}
