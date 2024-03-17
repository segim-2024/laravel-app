<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('orders.order_list', [
            // 'orders' => $orders,
        ]);
    }
}
