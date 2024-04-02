<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberSubscribeProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SSOController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sso-auth', [SSOController::class, 'handleSSO'])->name('sso.auth');
Route::get('/home', function () {
    return redirect("https://epamus.com/");
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/payments', [MemberPaymentController::class, 'index'])->name('payments.index');

    Route::get('/cards', [MemberCardController::class, 'index'])->name('cards.index');
    Route::get('/cards/is-exists', [MemberCardController::class, 'isExists'])->name('cards.is-exists');
    Route::get('/cards/created', [MemberCardController::class, 'created'])->name('cards.created');
    Route::get('/cards/failed', [MemberCardController::class, 'failed'])->name('cards.failed');
    Route::post('/cards', [MemberCardController::class, 'store'])->name('cards.store');

    Route::get('/products', [MemberSubscribeProductController::class, 'index'])->name('products.index');
    Route::post('/products/subscribe', [MemberSubscribeProductController::class, 'subscribe'])->name('products.subscribe');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
