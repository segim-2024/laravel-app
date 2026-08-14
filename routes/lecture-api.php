<?php

use App\Http\Controllers\LectureFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lecture API Routes
|--------------------------------------------------------------------------
|
| 가비아 관리자(adm/lecture_presign.php)에서 호출하는 강의 파일 관련 API.
| S3(segim-edu) 업로드용 presigned URL 발급과 객체 삭제를 담당한다.
|
| 인증: 파머스(새김) 로그인 토큰(Sanctum)이 필요하다. 고래영어 회원은 접근할 수 없다.
|
*/

Route::middleware('auth:sanctum')->group(static function () {
    Route::post('/lecture-files/presigned-url', [LectureFileController::class, 'presignedUrl']);
    Route::delete('/lecture-files', [LectureFileController::class, 'destroy']);
});
