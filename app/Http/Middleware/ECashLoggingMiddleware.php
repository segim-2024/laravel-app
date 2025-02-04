<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ECashLoggingMiddleware
{
    private const MONITORED_ENDPOINTS = [
        'api/e-cash/manually-charge',
        'api/e-cash/manually-spend',
        'api/e-cash/order2'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 모니터링할 엔드포인트인지 확인
        if (!in_array($request->path(), self::MONITORED_ENDPOINTS)) {
            return $next($request);
        }

        // 디버그 로그 추가
        Log::info('ECashLoggingMiddleware started', [
            'uri' => $request->path(),
            'token' => $request->bearerToken() ?? 'no-token'
        ]);

        $startTime = microtime(true);

        try {
            // 요청 처리
            $response = $next($request);

            // 디버그 로그 추가
            Log::info('Response received from next middleware', [
                'status' => $response->getStatusCode()
            ]);

            // 응답 생성 후 시간 계산
            $duration = microtime(true) - $startTime;

            // Bearer 토큰 추출
            $bearerToken = $request->bearerToken() ?? 'no-token';

            // 로그 데이터 구성
            $logData = [
                'token' => $bearerToken,
                'method' => $request->method(),
                'uri' => $request->path(),
                'payload' => $request->all(),
                'headers' => $this->getFilteredHeaders($request),
                'response' => [
                    'status' => $response->getStatusCode(),
                    'duration' => round($duration * 1000, 2) . 'ms'
                ]
            ];

            // 상태 코드에 따라 로그 레벨 결정
            $logLevel = $response->getStatusCode() >= 400 ? 'warning' : 'info';

            // 로그 기록
            Log::log($logLevel, 'E-Cash API Request', $logData);


            return $response;

        } catch (Throwable $e) {
            // 디버그 로그 추가
            Log::error('Exception caught in middleware', [
                'message' => $e->getMessage(),
                'class' => get_class($e)
            ]);

            $duration = microtime(true) - $startTime;

            $logData = [
                'token' => $request->bearerToken() ?? 'no-token',
                'method' => $request->method(),
                'uri' => $request->path(),
                'payload' => $request->all(),
                'headers' => $this->getFilteredHeaders($request),
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'duration' => round($duration * 1000, 2) . 'ms'
                ]
            ];

            Log::warning('E-Cash API Error', $logData);
            throw $e;
        }
    }

    /**
     * 필요한 헤더만 필터링하여 반환
     *
     * @param Request $request
     * @return array
     */
    private function getFilteredHeaders(Request $request): array
    {
        $headers = $request->headers->all();

        // 민감한 정보가 포함될 수 있는 헤더 제외
        $excludedHeaders = [
            'cookie',
            'x-xsrf-token',
            'php-auth-pw'
        ];

        return array_filter(
            $headers,
            fn($key) => !in_array(strtolower($key), $excludedHeaders),
            ARRAY_FILTER_USE_KEY
        );
    }
}
