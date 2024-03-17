<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\MemberPaymentServiceInterface;
use Illuminate\Http\Request;

class MemberPaymentController extends Controller
{
    public function __construct(
        protected MemberPaymentServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        $payments = $this->service->getList($request->user());
        return view('payments.payment_list', [
            'payments' => $payments
        ]);
    }
}
