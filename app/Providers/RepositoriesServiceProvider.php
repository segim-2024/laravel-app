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
        $this->app->bind(\App\Repositories\Interfaces\LibraryProductRepositoryInterface::class, \App\Repositories\Eloquent\LibraryProductRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\LibraryProductSubscribeRepositoryInterface::class, \App\Repositories\Eloquent\LibraryProductSubscribeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\LibraryPaymentRepositoryInterface::class, \App\Repositories\Eloquent\LibraryPaymentRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\ProductPaymentRepositoryInterface::class, \App\Repositories\Eloquent\ProductPaymentRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\LibraryPaymentApiLogRepositoryInterface::class, \App\Repositories\Eloquent\LibraryPaymentApiLogRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorEssayNoticeRepositoryInterface::class, \App\Repositories\Eloquent\DoctorEssayNoticeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorEssaySeriesRepositoryInterface::class, \App\Repositories\Eloquent\DoctorEssaySeriesRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorEssayVolumeRepositoryInterface::class, \App\Repositories\Eloquent\DoctorEssayVolumeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorEssayLessonRepositoryInterface::class, \App\Repositories\Eloquent\DoctorEssayLessonRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DoctorEssayMaterialRepositoryInterface::class, \App\Repositories\Eloquent\DoctorEssayMaterialRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\WhaleMemberCashRepositoryInterface::class, \App\Repositories\Eloquent\WhaleMemberCashRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\WhaleMemberCashTransactionRepositoryInterface::class, \App\Repositories\Eloquent\WhaleMemberCashTransactionRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\OrderSegimTicketPlusLogRepositoryInterface::class, \App\Repositories\Eloquent\OrderSegimTicketPlusLogPlusLogRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\ItemRepositoryInterface::class, \App\Repositories\Eloquent\ItemRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\CartRepositoryInterface::class, \App\Repositories\Eloquent\CartRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\ReturnItemRepositoryInterface::class, \App\Repositories\Eloquent\ReturnItemRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\OrderSegimTicketMinusLogRepositoryInterface::class, \App\Repositories\Eloquent\OrderSegimTicketMinusLogRepository::class);
    }

    public function boot()
    {

    }
}
