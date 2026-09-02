<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 라이브러리 서버(외주)는 고정 IP 한 곳에서 호출하므로 IP 기준 제한을 쓰면
        // 전체 트래픽이 한 버킷을 공유한다. 예상 호출량을 알 수 없어 현재는 제한하지 않는다.
        // 접근 제어는 CheckLibraryServerMiddleware 의 IP 허용 목록 + API 키가 담당한다.
        // 제한이 필요해지면 이 반환값만 Limit::perMinute(n) 으로 바꾸면 된다.
        RateLimiter::for('library-api', function (Request $request) {
            return Limit::none();
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('api')
                ->prefix('file-api')
                ->group(base_path('routes/file-api.php'));

            // 'api' 그룹(throttle:api = 60/min) 대신 전용 limiter 를 쓴다
            Route::middleware(['throttle:library-api', SubstituteBindings::class])
                ->prefix('library-api')
                ->group(base_path('routes/library-api.php'));

            // DEPRECATED: 가비아가 /file-api 로 전환을 마치면 제거한다
            Route::middleware('api')
                ->prefix('lecture-api')
                ->group(base_path('routes/lecture-api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
