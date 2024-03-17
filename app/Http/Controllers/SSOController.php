<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\Interfaces\MemberCashServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSOController extends Controller
{
    public function __construct(
        protected MemberCashServiceInterface $cashService
    ) {}

    public function handleSSO(Request $request): RedirectResponse
    {
        // SSO 인증 로직 구현
        // 예시로, SSO 토큰을 검증하고 사용자 정보를 조회하는 과정을 가정합니다.

        // SSO 토큰 검증 및 사용자 정보 조회
        $member = $this->authenticateWithSSO($request);
        if (! $member) {
            // 인증 실패 시, 로그인 페이지나 에러 페이지로 리디렉션
            return redirect()->route('login')->withErrors('SSO 인증 실패');
        }

        if (! $member->cash) {
            $this->cashService->create($member);
        }

        // Laravel 내부 인증 시스템을 사용하여 사용자 세션 생성
        Auth::login($member);

        // 인증 성공 시, /main 라우트로 리디렉션
        return redirect()->route('cards.index');
    }

    private function authenticateWithSSO(Request $request): ?Member
    {
        return Member::where('mb_id', '=', 'test01')->first();
    }
}
