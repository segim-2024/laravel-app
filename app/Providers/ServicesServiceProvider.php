<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\App\Services\Interfaces\MemberCardServiceInterface::class, \App\Services\MemberCardService::class);
        $this->app->bind(\App\Services\Interfaces\ProductServiceInterface::class, \App\Services\ProductService::class);
        $this->app->bind(\App\Services\Interfaces\MemberCashServiceInterface::class, \App\Services\MemberCashService::class);
        $this->app->bind(\App\Services\Interfaces\MemberSubscribeProductServiceInterface::class, \App\Services\MemberSubscribeProductService::class);
        $this->app->bind(\App\Services\Interfaces\MemberPaymentServiceInterface::class, \App\Services\MemberPaymentService::class);
        $this->app->bind(\App\Services\Interfaces\TossServiceInterface::class, \App\Services\TossService::class);
        $this->app->bind(\App\Services\Interfaces\MemberServiceInterface::class, \App\Services\MemberService::class);
        $this->app->bind(\App\Services\Interfaces\MemberCashTransactionServiceInterface::class, \App\Services\MemberCashTransactionService::class);
        $this->app->bind(\App\Services\Interfaces\OrderServiceInterface::class, \App\Services\OrderService::class);
        $this->app->bind(\App\Services\Interfaces\PortOneServiceInterface::class, \App\Services\PortOneService::class);
    }

    public function boot()
    {

    }
}
