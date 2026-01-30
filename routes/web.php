<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryPaymentController;
use App\Http\Controllers\LibraryProductSubscribeController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberSubscribeProductController;
use App\Http\Controllers\MileageController;
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
Route::get('/whale-sso-auth', [SSOController::class, 'handleWhaleSSO'])->name('whale.sso.auth');

Route::get('/home', function () {
    return redirect("https://epamus.com/");
})->name('home');

Route::get('/whale-home', function () {
    return redirect("https://englishwhale.com/");
})->name('whale.home');

Route::middleware(['auth:web,whale'])->group(function () {
    Route::get('/mileage', [MileageController::class, 'index'])->name('mileage.index');
    Route::get('/mileage/list', [MileageController::class, 'list'])->name('mileage.list');
    Route::get('/mileage/status', [MileageController::class, 'status'])->name('mileage.status');
    Route::post('/mileage/convert', [MileageController::class, 'convert'])->name('mileage.convert');

    Route::get('/payments', [MemberPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/list', [MemberPaymentController::class, 'list'])->name('payments.list');

    Route::get('/cards', [MemberCardController::class, 'index'])->name('cards.index');
    Route::get('/cards/is-exists', [MemberCardController::class, 'isExists'])->name('cards.is-exists');
    Route::post('/cards', [MemberCardController::class, 'store'])->name('cards.store');

    Route::get('/products', [MemberSubscribeProductController::class, 'index'])->name('products.index');
    Route::get('/products/list', [MemberSubscribeProductController::class, 'list'])->name('products.list');
    Route::get('/products/check-card-registered', [MemberSubscribeProductController::class, 'checkExistsSubscribed'])->name('products.check-card-registered');
    Route::post('/products/subscribe', [MemberSubscribeProductController::class, 'subscribe'])->name('products.subscribe');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/list', [OrderController::class, 'list'])->name('orders.list');

    Route::get('/library-payments', [LibraryPaymentController::class, 'index'])->name('library-payments.index');
    Route::get('/library-payments/list', [LibraryPaymentController::class, 'list'])->name('library-payments.list');

    Route::get('/library-products', [LibraryProductSubscribeController::class, 'index'])->name('library-products.index');
    Route::post('/library-products/{productId}/subscribe', [LibraryProductSubscribeController::class, 'subscribe'])->name('library-products.subscribe');
    Route::patch('/library-products/{productId}/unsubscribe', [LibraryProductSubscribeController::class, 'unsubscribe'])->name('library-products.unsubscribe');
    Route::patch('/library-products/{productId}/card', [LibraryProductSubscribeController::class, 'updateCard'])->name('library-products.update-card');
    Route::post('/library-products/{productId}/re-payment', [LibraryProductSubscribeController::class, 'rePayment'])->name('library-products.re-payment');
    Route::get('/library-products/check-can-subscribe', [LibraryProductSubscribeController::class, 'checkCanSubscribe'])->name('library-products.check-can-subscribe');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
