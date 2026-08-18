<?php

use App\Http\Controllers\S3FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lecture API Routes (DEPRECATED)
|--------------------------------------------------------------------------
|
| 강의박사 전용이던 시절의 구 경로. routes/file-api.php 로 대체되었으며
| 동작은 완전히 동일하다 (같은 컨트롤러 · 같은 요청/응답 형식).
|
| 가비아가 /file-api 로 전환을 마치면 이 파일과 RouteServiceProvider 의
| lecture-api 프리픽스 등록을 함께 제거한다.
|
*/

Route::middleware('auth:sanctum')->group(static function () {
    Route::post('/lecture-files/presigned-url', [S3FileController::class, 'presignedUrl']);
    Route::delete('/lecture-files', [S3FileController::class, 'destroy']);
});
