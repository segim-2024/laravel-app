<?php

namespace App\Console\Commands;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\Models\WhaleMemberSubscribeProduct;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class WhaleMemberSubscribeProductMakeStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whale-member-subscribe-product-make-start-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '고래영어: 이전 달에 생성된 구독 상품을 시작으로 변경합니다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 지난달의 시작일과 종료일 계산
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $logService = app(MemberSubscribeProductServiceInterface::class);

        // 지난달에 생성된 레코드를 필터링하여 'is_started' 필드를 true로 업데이트
        $updatedCount = WhaleMemberSubscribeProduct::with('member')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->each(function (WhaleMemberSubscribeProduct $subscribeProduct) use ($logService) {
                $subscribeProduct->is_started = true;
                $subscribeProduct->save();
                $logService->logging($subscribeProduct->member, MemberSubscribeProductLogDTO::started($subscribeProduct));
            });

        $this->info("고래영어: Updated $updatedCount records between $startOfLastMonth and $endOfLastMonth.");
    }
}
