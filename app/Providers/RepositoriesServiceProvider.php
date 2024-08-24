<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class,  BaseRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberCardRepositoryInterface::class, \App\Repositories\Eloquent\MemberCardRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface::class, \App\Repositories\Eloquent\MemberSubscribeProductRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\ProductRepositoryInterface::class, \App\Repositories\Eloquent\ProductRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberCashRepositoryInterface::class, \App\Repositories\Eloquent\MemberCashRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberPaymentRepositoryInterface::class, \App\Repositories\Eloquent\MemberPaymentRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberRepositoryInterface::class, \App\Repositories\Eloquent\MemberRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface::class, \App\Repositories\Eloquent\MemberCashTransactionRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\OrderRepositoryInterface::class, \App\Repositories\Eloquent\OrderRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface::class, \App\Repositories\Eloquent\MemberSubscribeProductLogRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorFileSeriesRepositoryInterface::class, \App\Repositories\Eloquent\DoctorFileSeriesRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorFileVolumeRepositoryInterface::class, \App\Repositories\Eloquent\DoctorFileVolumeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorFileLessonRepositoryInterface::class, \App\Repositories\Eloquent\DoctorFileLessonRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorFileLessonMaterialRepositoryInterface::class, \App\Repositories\Eloquent\DoctorFileLessonMaterialRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorFileNoticeRepositoryInterface::class, \App\Repositories\Eloquent\DoctorFileNoticeRepository::class);
    }

    public function boot()
    {

    }
}
