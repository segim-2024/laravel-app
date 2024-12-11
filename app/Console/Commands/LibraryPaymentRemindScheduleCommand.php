<?php

namespace App\Console\Commands;

use App\Jobs\SendLibraryPaymentRemindTokJob;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
use Illuminate\Console\Command;

class LibraryPaymentRemindScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:library-payment-remind-schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '라이브러리 서비스 구독 결제 리마인드 알림 스케줄러';

    /**
     * Execute the console command.
     */
    public function handle(
        LibraryProductSubscribeServiceInterface $service,
    )
    {
        // 내일 결제 예정인 라이브러리 구독 목록을 조회합니다.
        $subscriptions = $service->getSubscriptionsDueTomorrow();

        // 루프를 돌며 알림톡을 발송합니다.
        foreach ($subscriptions as $subscribe) {
            SendLibraryPaymentRemindTokJob::dispatch($subscribe);
        }
    }
}
