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
        $schedule->command('app:member-subscribe-product-make-start-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->monthlyOn(1, '00:10');

        $schedule->command('app:subscribe-product-payment-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('09:00');

        $schedule->command('app:library-subscribe-payment-schedule-command')
            ->runInBackground()
            ->withoutOverlapping()
            ->dailyAt('09:00');

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
