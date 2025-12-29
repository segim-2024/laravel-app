<?php

use App\Http\Controllers\DoctorEssayLessonController;
use App\Http\Controllers\DoctorEssayMaterialController;
use App\Http\Controllers\DoctorEssayNoticeController;
use App\Http\Controllers\DoctorEssaySeriesController;
use App\Http\Controllers\DoctorEssayVolumeController;
use App\Http\Controllers\DoctorFileLessonController;
use App\Http\Controllers\DoctorFileLessonMaterialController;
use App\Http\Controllers\DoctorFileNoticeController;
use App\Http\Controllers\DoctorFileSeriesController;
use App\Http\Controllers\DoctorFileVolumeController;
use App\Http\Controllers\LibraryProductController;
use App\Http\Controllers\LibraryProductSubscribeApiController;
use App\Http\Controllers\MemberCardApiController;
use App\Http\Controllers\MemberCashController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberSubscribeProductController;
use App\Http\Controllers\OrderAlimTokController;
use App\Http\Controllers\OrderSegimTicketController;
use App\Http\Controllers\PortOneWebHookController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\TossWebHookController;
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

    Route::post('/alim-tok/payment', [OrderAlimTokController::class, 'payment']);
    Route::post('/alim-tok/deposit-guidance', [OrderAlimTokController::class, 'depositGuidance']);
    Route::post('/alim-tok/shipment-track', [OrderAlimTokController::class, 'shipmentTrack']);

    Route::get('/library-products', [LibraryProductController::class, 'index']);
    Route::post('/library-products', [LibraryProductController::class, 'store']);
    Route::patch('/library-products/{productId}', [LibraryProductController::class, 'update']);

    Route::post('/library-products/members/unsubscribe', [LibraryProductSubscribeApiController::class, 'unsubscribe']);

    Route::post('/segim/orders/tickets/plus', [OrderSegimTicketController::class, 'plus']);
    Route::post('/segim/orders/tickets/minus', [OrderSegimTicketController::class, 'minus']);
});


Route::post('/sign-in', [SignInController::class, 'signIn'])->middleware('checkPamusIP');
Route::post('/sign-in/app', [SignInController::class, 'signInApp']);

Route::middleware(['auth:sanctum'])->group(function () {
    /* 이캐쉬 - 수동 충전, 수동 소모 */
    Route::post('/e-cash/manually-charge', [MemberCashController::class, 'charge']);
    Route::post('/e-cash/manually-spend', [MemberCashController::class, 'spend']);
    Route::post('/e-cash/manual-charge', [MemberCashController::class, 'manualCharge']);
    Route::post('/e-cash/manual-spend', [MemberCashController::class, 'manualSpend']);
    Route::post('/e-cash/order2', [MemberCashController::class, 'order2']);

    Route::get('/e-cash/histories/excel', [MemberCashController::class, 'excel']);

    /* 카드 관리 */
    Route::get('/members/cards', [MemberCardApiController::class, 'index']);
    Route::delete('/members/cards/{id}', [MemberCardApiController::class, 'destroy']);

    /* 결제 관리 */
    Route::post('/payments/retry', [MemberPaymentController::class, 'retry']);
    Route::delete('/payments/{paymentId}', [MemberPaymentController::class, 'destroyFailedPayment']);
    Route::post('/payments/cancel', [MemberPaymentController::class, 'cancel']);

    /* 구독 관리 */
    Route::patch('/products/{productId}/subscribes/activate', [MemberSubscribeProductController::class, 'updateActivate']);
    Route::post('/products/{productId}/unsubscribe', [MemberSubscribeProductController::class, 'unsubscribe']);

    /* 자료박사 - 공지 [리스트, 입력, 수정, 삭제] */
    Route::get('/doctor-file/notices', [DoctorFileNoticeController::class, 'index']);
    Route::post('/doctor-file/notices', [DoctorFileNoticeController::class, 'store']);
    Route::patch('/doctor-file/notices/{noticeId}', [DoctorFileNoticeController::class, 'update']);
    Route::delete('/doctor-file/notices/{noticeId}', [DoctorFileNoticeController::class, 'destroy']);
    /* 자료박사 - 시리즈 [리스트, 삭제] */
    Route::get('/doctor-file/series', [DoctorFileSeriesController::class, 'index']);
    Route::delete('/doctor-file/series/{uuid}', [DoctorFileSeriesController::class, 'destroy']);
    /* 자료박사 - 볼륨 [가져오기, 표지 수정, 출력 여부 수정, 설명 수정, 삭제] */
    Route::get('/doctor-file/volumes/{uuid}', [DoctorFileVolumeController::class, 'show']);
    Route::post('/doctor-file/volumes/{uuid}/poster', [DoctorFileVolumeController::class, 'updatePoster']);
    Route::patch('/doctor-file/volumes/{uuid}/description', [DoctorFileVolumeController::class, 'updateDescription']);
    Route::patch('/doctor-file/volumes/{uuid}/is-published', [DoctorFileVolumeController::class, 'updateIsPublished']);
    Route::patch('/doctor-file/volumes/{uuid}/url', [DoctorFileVolumeController::class, 'updateUrl']);
    Route::delete('/doctor-file/volumes/{uuid}', [DoctorFileVolumeController::class, 'destroy']);
    /* 자료박사 - 레쓴 [가져오기, 삭제] */
    Route::get('/doctor-file/lessons/{uuid}', [DoctorFileLessonController::class, 'show']);
    Route::delete('/doctor-file/lessons/{uuid}', [DoctorFileLessonController::class, 'destroy']);
    /* 자료박사 - 자료 [입력, 수정, 삭제] */
    Route::post('/doctor-file/lessons/{uuid}/materials', [DoctorFileLessonMaterialController::class, 'store']);
    Route::patch('/doctor-file/lessons/materials/{uuid}', [DoctorFileLessonMaterialController::class, 'update']);
    Route::delete('/doctor-file/lessons/materials/{uuid}', [DoctorFileLessonMaterialController::class, 'destroy']);

    Route::middleware(['referrer.check'])->group(function () {
        /* 논술 박사 - 공지 [리스트, 입력, 수정, 삭제] */
        Route::get('/doctor-essay/notices', [DoctorEssayNoticeController::class, 'index']);
        Route::post('/doctor-essay/notices', [DoctorEssayNoticeController::class, 'store']);
        Route::patch('/doctor-essay/notices/{noticeId}', [DoctorEssayNoticeController::class, 'update']);
        Route::delete('/doctor-essay/notices/{noticeId}', [DoctorEssayNoticeController::class, 'destroy']);
        /* 논술 박사 - 시리즈 [리스트, 삭제] */
        Route::get('/doctor-essay/series', [DoctorEssaySeriesController::class, 'index']);
        Route::delete('/doctor-essay/series/{uuid}', [DoctorEssaySeriesController::class, 'destroy']);
        /* 논술 박사 - 볼륨 [가져오기, 표지 수정, 출력 여부 수정, 설명 수정, 삭제] */
        Route::get('/doctor-essay/volumes/{uuid}', [DoctorEssayVolumeController::class, 'show']);
        Route::post('/doctor-essay/volumes/{uuid}/poster', [DoctorEssayVolumeController::class, 'updatePoster']);
        Route::patch('/doctor-essay/volumes/{uuid}/description', [DoctorEssayVolumeController::class, 'updateDescription']);
        Route::patch('/doctor-essay/volumes/{uuid}/is-published', [DoctorEssayVolumeController::class, 'updateIsPublished']);
        Route::patch('/doctor-essay/volumes/{uuid}/url', [DoctorEssayVolumeController::class, 'updateUrl']);
        Route::delete('/doctor-essay/volumes/{uuid}', [DoctorEssayVolumeController::class, 'destroy']);
        /* 논술 박사 - 레쓴 [가져오기, 삭제] */
        Route::get('/doctor-essay/lessons/{uuid}', [DoctorEssayLessonController::class, 'show']);
        Route::delete('/doctor-essay/lessons/{uuid}', [DoctorEssayLessonController::class, 'destroy']);
        /* 자료박사 - 자료 [입력, 수정, 삭제] */
        Route::post('/doctor-essay/lessons/{uuid}/materials', [DoctorEssayMaterialController::class, 'store']);
        Route::patch('/doctor-essay/lessons/materials/{uuid}', [DoctorEssayMaterialController::class, 'update']);
        Route::delete('/doctor-essay/lessons/materials/{uuid}', [DoctorEssayMaterialController::class, 'destroy']);
    });
});

Route::post('/toss/web-hook', [TossWebHookController::class, 'index']);
Route::post('/port-one/web-hook', [PortOneWebHookController::class, 'webHook']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
