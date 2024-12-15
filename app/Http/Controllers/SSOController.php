<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\Interfaces\MemberCashServiceInterface;
use App\Services\Interfaces\MemberServiceInterface;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SSOController extends Controller
{
    public function __construct(
        protected MemberCashServiceInterface $cashService,
        protected MemberServiceInterface $memberService
    ) {}

    public function handleSSO(Request $request): RedirectResponse
    {
        // SSO 토큰을 검증하고 사용자 정보를 조회하는 과정을 가정합니다.
        try {
            $member = $this->authenticateWithSSO($request);
            if (! $member) {
                throw new RuntimeException("유효하지 않은 사용자 정보입니다.");
            }
        } catch (Exception $e) {
            // 인증 실패 시, 로그인 페이지나 에러 페이지로 리디렉션
            Log::warning($e);
            return redirect()->route('home');
        }

        // E-Cash 데이터가 없으면 생성
        if (! $member->cash) {
            $this->cashService->create($member);
        }

        // Laravel 내부 인증 시스템 사용자 세션 생성 후 서비스 페이지 진입
        Auth::login($member);

        // redirect_route 파라미터가 있는 경우 해당 경로로 리다이렉션
        $redirectRoute = $request->query('redirect_route');
        if ($redirectRoute === 'library-products') {
            return redirect()->route('library-products.index');
        }

        return redirect()->route('cards.index');
    }

    private function authenticateWithSSO(Request $request): ?Member
    {
        if (Config::get('app.env') === 'local') {
            return Member::where('mb_id', '=', 'test01')->first();
        }

        // 쿼리스트링에서 암호화된 데이터 받기
        try {
            $secureData = $request->input('data');
            [$dataEncoded, $signature] = explode('.', $secureData);

            // 서명 검증
            if (hash_equals(hash_hmac('sha256', $dataEncoded, Config::get('services.sso.key')), $signature)) {
                $data = json_decode(base64_decode($dataEncoded), true, 512, JSON_THROW_ON_ERROR);
                Log::info(json_encode($data, JSON_THROW_ON_ERROR));
                if (! $data['mb_id']) {
                    return null;
                }

                // 데이터 사용, 예: 사용자 인증
                return Member::where('mb_id', '=', $data['mb_id'])->first();
            }
        } catch (Exception $exception) {
            Log::warning($exception);
            throw new RuntimeException("유효하지 않은 토큰 정보입니다.");
        }

        return null;
    }
}
