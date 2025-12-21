<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\MemberInterface;
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

    /**
     * 파머스영어(새김) SSO 인증 처리
     */
    public function handleSSO(Request $request): RedirectResponse
    {
        try {
            $memberId = $this->extractMemberIdFromSSO($request->input('data'));
            $member = $this->memberService->find($memberId, isWhale: false);

            if (! $member) {
                throw new RuntimeException("유효하지 않은 사용자 정보입니다.");
            }

            $this->loginMember($member);

            $redirectRoute = $request->query('redirect_route');
            if ($redirectRoute === 'library-products') {
                return redirect()->route('library-products.index');
            }

            return redirect()->route('cards.index');
        } catch (Exception $e) {
            Log::warning($e);
            return redirect()->route('home');
        }
    }

    /**
     * 고래영어 SSO 인증 처리
     */
    public function handleWhaleSSO(Request $request): RedirectResponse
    {
        try {
            $memberId = $this->extractMemberIdFromSSO($request->input('data'));
            $member = $this->memberService->find($memberId, isWhale: true);

            if (! $member) {
                throw new RuntimeException("유효하지 않은 사용자 정보입니다.");
            }

            $this->loginMember($member);

            return redirect()->route('cards.index');
        } catch (Exception $e) {
            Log::warning($e);
            return redirect()->route('whale.home');
        }
    }

    /**
     * 멤버 로그인 처리 (E-Cash 생성 포함)
     */
    private function loginMember(MemberInterface $member): void
    {
        if (! $member->getCash()) {
            $this->cashService->create($member);
        }

        Auth::login($member);
    }

    /**
     * SSO 데이터에서 mb_id 추출
     */
    private function extractMemberIdFromSSO(?string $secureData): string
    {
        if (Config::get('app.env') === 'local') {
            return 'test01';
        }

        if (! $secureData) {
            throw new RuntimeException("SSO 데이터가 없습니다.");
        }

        [$dataEncoded, $signature] = explode('.', $secureData);

        if (! hash_equals(hash_hmac('sha256', $dataEncoded, Config::get('services.sso.key')), $signature)) {
            throw new RuntimeException("유효하지 않은 서명입니다.");
        }

        $data = json_decode(base64_decode($dataEncoded), true, 512, JSON_THROW_ON_ERROR);
        Log::info(json_encode($data, JSON_THROW_ON_ERROR));

        if (empty($data['mb_id'])) {
            throw new RuntimeException("mb_id가 없습니다.");
        }

        return $data['mb_id'];
    }
}
