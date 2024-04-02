<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('toss', function () {
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode(Config::get('toss.secret_key')),
            ])->baseUrl('https://api.tosspayments.com/v1');
        });
    }
}
