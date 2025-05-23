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
        $this->app->bind(\App\Services\Interfaces\AlimTokClientServiceInterface::class, \App\Services\AlimTokClientService::class);
        $this->app->bind(\App\Services\Interfaces\OrderAlimTokServiceInterface::class, \App\Services\OrderAlimTokService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorFileSeriesServiceInterface::class, \App\Services\DoctorFileSeriesService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorFileVolumeServiceInterface::class, \App\Services\DoctorFileVolumeService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorFileLessonServiceInterface::class, \App\Services\DoctorFileLessonService::class);
        $this->app->bind(\App\Services\Interfaces\FileServiceInterface::class, \App\Services\FileService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface::class, \App\Services\DoctorFileLessonMaterialService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorFileNoticeServiceInterface::class, \App\Services\DoctorFileNoticeService::class);
        $this->app->bind(\App\Services\Interfaces\LibraryProductServiceInterface::class, \App\Services\LibraryProductService::class);
        $this->app->bind(\App\Services\Interfaces\LibraryProductSubscribeServiceInterface::class, \App\Services\LibraryProductSubscribeService::class);
        $this->app->bind(\App\Services\Interfaces\LibraryPaymentServiceInterface::class, \App\Services\LibraryPaymentService::class);
        $this->app->bind(\App\Services\Interfaces\ProductPaymentServiceInterface::class, \App\Services\ProductPaymentService::class);
        $this->app->bind(\App\Services\Interfaces\LibraryApiServiceInterface::class, \App\Services\LibraryApiService::class);
        $this->app->bind(\App\Services\Interfaces\LibraryPaymentApiLogServiceInterface::class, \App\Services\LibraryPaymentApiLogService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorEssayNoticeServiceInterface::class, \App\Services\DoctorEssayNoticeService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorEssaySeriesServiceInterface::class, \App\Services\DoctorEssaySeriesService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorEssayVolumeServiceInterface::class, \App\Services\DoctorEssayVolumeService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorEssayLessonServiceInterface::class, \App\Services\DoctorEssayLessonService::class);
        $this->app->bind(\App\Services\Interfaces\DoctorEssayMaterialServiceInterface::class, \App\Services\DoctorEssayMaterialService::class);
        $this->app->bind(\App\Services\Interfaces\OrderSegimTicketServiceInterface::class, \App\Services\OrderSegimTicketService::class);
        $this->app->bind(\App\Services\Interfaces\CartServiceInterface::class, \App\Services\CartService::class);
        $this->app->bind(\App\Services\Interfaces\ReturnItemServiceInterface::class, \App\Services\ReturnItemService::class);
    }

    public function boot()
    {

    }
}
