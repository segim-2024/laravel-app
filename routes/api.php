<?php

use App\Http\Controllers\MemberCashController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberSubscribeProductController;
use App\Http\Middleware\CheckFromPamusMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => CheckFromPamusMiddleware::class], static function () {
    Route::post('/e-cash/order', [MemberCashController::class, 'order']);
    Route::post('/e-cash/manual-charge', [MemberCashController::class, 'manualCharge']);
    Route::post('/e-cash/manual-spend', [MemberCashController::class, 'manualSpend']);
    Route::post('/payments/retry', [MemberPaymentController::class, 'retry']);
    Route::patch('/products/{productId}/subscribes/activate', [MemberSubscribeProductController::class, 'updateActivate']);
    Route::post('/products/{productId}/unsubscribe', [MemberSubscribeProductController::class, 'unsubscribe']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
