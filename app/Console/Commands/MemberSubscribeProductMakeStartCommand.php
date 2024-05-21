<?php

namespace App\Console\Commands;

use App\Models\MemberSubscribeProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MemberSubscribeProductMakeStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:member-subscribe-product-make-start-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '이전 달에 생성된 구독 상품을 시작으로 변경합니다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 지난달의 시작일과 종료일 계산
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 지난달에 생성된 레코드를 필터링하여 'is_started' 필드를 true로 업데이트
        $updatedCount = MemberSubscribeProduct::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->update(['is_started' => true]);

        $this->info("Updated $updatedCount records between $startOfLastMonth and $endOfLastMonth.");
    }
}
