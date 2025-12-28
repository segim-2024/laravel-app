<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 파머스영어 구독 시작 처리 (매월 1일)
        $schedule->command('app:member-subscribe-product-make-start-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->monthlyOn(1, '00:10');

        // 고래영어 구독 시작 처리 (매월 1일)
        $schedule->command('app:whale-member-subscribe-product-make-start-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->monthlyOn(1, '00:15');

        // 파머스영어 정기결제 (매일 09:00)
        $schedule->command('app:subscribe-product-payment-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('09:00');

        // 고래영어 정기결제 (매일 09:05)
        $schedule->command('app:whale-subscribe-product-payment-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('09:05');

        // 라이브러리 구독 결제 (파머스영어 전용)
        $schedule->command('app:library-subscribe-payment-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('09:00');

        // 라이브러리 결제 알림 (파머스영어 전용)
        $schedule->command('app:library-payment-remind-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('16:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
