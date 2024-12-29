<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ReferrerCheckMiddleware
{
    private const WHALE_DOMAINS = [
        'https://englishwhale.com'
    ];

    private const EPAMUS_DOMAINS = [
        'https://epamus.com'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // FormRequest로 변환될 때까지 attributes에 임시 저장
        $isWhale = $this->determineIsWhale($request);
        $request->attributes->set('isWhale', $isWhale);

        return $next($request);
    }

    private function determineIsWhale(Request $request): bool
    {
        $referer = $request->headers->get('referer');

        // 로컬 환경 체크
        if (!$referer && Config::get('app.env') === 'local') {
            return true;
        }

        // Whale 도메인 체크
        if ($this->matchesDomain($referer, self::WHALE_DOMAINS)) {
            return true;
        }

        // Epamus 도메인 체크
        if ($this->matchesDomain($referer, self::EPAMUS_DOMAINS)) {
            return false;
        }

        return true;
        // throw new AccessDeniedHttpException('올바른 접근이 아닙니다.');
    }

    private function matchesDomain(?string $referer, array $domains): bool
    {
        if (!$referer) {
            return false;
        }

        foreach ($domains as $domain) {
            if (str_contains($referer, $domain)) {
                return true;
            }
        }

        return false;
    }
}
