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

        Http::macro('portone', function () {
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'PortOne '.Config::get('services.portone.v2.key'),
            ])->baseUrl('https://api.portone.io');
        });

        Http::macro('mts', function () {
            return Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->baseUrl('https://api.mtsco.co.kr/');
        });

        Http::macro('library', function () {
            return Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->baseUrl('https://whale.cloubot.com/api')
                ->asForm();
        });
    }
}
