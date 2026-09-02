<?php

use App\Enums\MemberTargetEnum;
use App\Http\Controllers\LibraryMemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Library API Routes
|--------------------------------------------------------------------------
|
| 라이브러리 서버(외부 외주)에서 호출하는 회원 조회 API.
| 서버 간 인증은 CheckLibraryServerMiddleware(IP + API 키)가, 회원 인증은
| HTTP Basic 헤더가 담당한다.
|
*/

Route::middleware('library.server')->group(static function () {
    Route::get('/{target}/member', [LibraryMemberController::class, 'show'])
        ->whereIn('target', MemberTargetEnum::values());
});
