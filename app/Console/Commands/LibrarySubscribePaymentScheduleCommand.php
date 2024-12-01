<?php

namespace App\Console\Commands;

use App\Jobs\LibraryBillingPaymentJob;
use App\Models\LibraryProductSubscribe;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
use Illuminate\Console\Command;

class LibrarySubscribePaymentScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:library-subscribe-payment-schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '라이브러리 정기 결제를 실행합니다.';

    /**
     * Execute the console command.
     */
    public function handle(LibraryProductSubscribeServiceInterface $subscribeService)
    {
        $subscribes = $subscribeService->getSubscriptionsDueToday();
        if ($subscribes->count() < 1) {
            $this->info('No subscriptions due today.');
            return;
        }

        $subscribes->each(function (LibraryProductSubscribe $subscribe) {
            LibraryBillingPaymentJob::dispatch($subscribe->id);
        });

        $this->info('Okay : ' . $subscribes->count());
    }
}
