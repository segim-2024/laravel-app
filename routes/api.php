<?php

use App\Http\Controllers\DoctorFileLessonController;
use App\Http\Controllers\DoctorFileLessonMaterialController;
use App\Http\Controllers\DoctorFileNoticeController;
use App\Http\Controllers\DoctorFileSeriesController;
use App\Http\Controllers\DoctorFileVolumeController;
use App\Http\Controllers\MemberCashController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberSubscribeProductController;
use App\Http\Controllers\OrderAlimTokController;
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
    Route::post('/e-cash/manual-charge', [MemberCashController::class, 'manualCharge']);
    Route::post('/e-cash/manual-spend', [MemberCashController::class, 'manualSpend']);
    Route::post('/payments/retry', [MemberPaymentController::class, 'retry']);
    Route::post('/payments/cancel', [MemberPaymentController::class, 'cancel']);
    Route::patch('/products/{productId}/subscribes/activate', [MemberSubscribeProductController::class, 'updateActivate']);
    Route::post('/products/{productId}/unsubscribe', [MemberSubscribeProductController::class, 'unsubscribe']);

    Route::post('/alim-tok/payment', [OrderAlimTokController::class, 'payment']);
    Route::post('/alim-tok/deposit-guidance', [OrderAlimTokController::class, 'depositGuidance']);
    Route::post('/alim-tok/shipment-track', [OrderAlimTokController::class, 'shipmentTrack']);
});

/* 토큰 발급 */
Route::post('/sign-in', [SignInController::class, 'signIn'])->middleware('checkPamusIP');

Route::middleware(['auth:sanctum'])->group(function () {
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
    Route::delete('/doctor-file/volumes/{uuid}', [DoctorFileVolumeController::class, 'destroy']);
    /* 자료박사 - 레쓴 [가져오기, 파일 수정, 설명 수정, 삭제] */
    Route::get('/doctor-file/lessons/{uuid}', [DoctorFileLessonController::class, 'show']);
    Route::delete('/doctor-file/lessons/{uuid}', [DoctorFileLessonController::class, 'destroy']);
    /* 자료박사 - 자료 [입력, 삭제] */
    Route::post('/doctor-file/lessons/{uuid}/materials', [DoctorFileLessonMaterialController::class, 'store']);
    Route::delete('/doctor-file/lessons/materials/{uuid}', [DoctorFileLessonMaterialController::class, 'destroy']);
});

Route::post('/toss/web-hook', [TossWebHookController::class, 'index']);
Route::post('/port-one/web-hook', [PortOneWebHookController::class, 'webHook']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
