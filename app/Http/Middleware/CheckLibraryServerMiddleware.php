<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

/**
 * 라이브러리 서버(외부 외주)에서 온 요청인지 검증한다.
 *
 * 앱은 ALB 뒤에 있고 보안그룹이 ALB 경유만 허용하므로 요청은 반드시 ALB 를 통과한다.
 * ALB 는 X-Forwarded-For 뒤에 실제 peer IP 를 append 하고, TrustProxies 는 직접 연결된
 * ALB 노드(REMOTE_ADDR)만 신뢰하므로 $request->ip() 는 위조할 수 없다.
 */
class CheckLibraryServerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isAllowedIp($request->ip())) {
            return $this->deny('IP_NOT_ALLOWED');
        }

        if (! $this->hasValidApiKey($request)) {
            return $this->deny('INVALID_API_KEY');
        }

        return $next($request);
    }

    /**
     * 허용 IP 목록에 포함되는지 여부. 로컬 개발 환경에서는 IP 검사를 생략한다.
     */
    private function isAllowedIp(?string $ip): bool
    {
        if (Config::get('app.env') === 'local') {
            return true;
        }

        if ($ip === null) {
            return false;
        }

        return in_array($ip, $this->allowedIps(), true);
    }

    /**
     * 허용 IP 목록. 쉼표(,)와 파이프(|)를 모두 구분자로 받으며 항목의 공백은 무시한다.
     *
     * 목록이 비어 있으면 빈 배열을 반환해 모든 요청이 거부된다.
     *
     * @return array<int, string>
     */
    private function allowedIps(): array
    {
        $raw = (string) Config::get('services.library.inbound.ips');

        return array_values(array_filter(
            array_map('trim', preg_split('/[,|]/', $raw) ?: [])
        ));
    }

    /**
     * API 키가 일치하는지 여부. 키가 설정되어 있지 않으면 항상 거부한다.
     */
    private function hasValidApiKey(Request $request): bool
    {
        $expected = (string) Config::get('services.library.inbound.api_key');

        return $expected !== ''
            && hash_equals($expected, (string) $request->header('X-Library-Api-Key'));
    }

    private function deny(string $code): JsonResponse
    {
        return response()->json([
            'error' => 'Unauthorized',
            'code' => $code,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
