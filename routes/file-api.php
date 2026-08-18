<?php

use App\Http\Controllers\S3FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File API Routes
|--------------------------------------------------------------------------
|
| 가비아 관리자에서 호출하는 S3(segim-edu) 파일 관련 API.
| 업로드용 presigned URL 발급과 객체 삭제를 담당하며, 대상은
| S3FileTypeEnum 의 type 값으로 구분한다 (강의 영상/교육자료, 게시판 첨부 등).
|
| 구 경로 routes/lecture-api.php 는 하위 호환을 위해 함께 유지된다.
|
*/

Route::middleware('auth:sanctum')->group(static function () {
    Route::post('/files/presigned-url', [S3FileController::class, 'presignedUrl']);
    Route::delete('/files', [S3FileController::class, 'destroy']);
});
