<?php

namespace App\Console\Commands;

use App\Jobs\ProductBillingPaymentJob;
use App\Models\WhaleMemberSubscribeProduct;
use App\Models\WhaleProduct;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class WhaleSubscribeProductPaymentScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whale-subscribe-product-payment-schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '고래영어 정기결제를 실행합니다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->day;

        // 정기 결제일이 오늘인 상품 검색
        $products = WhaleProduct::where('payment_day', '=', $today)
            ->whereHas('subscribes', function (Builder $query) {
                $query
                    ->where('is_activated', '=', true)
                    ->where('is_started', '=', true)
                    ->whereHas('member');
            })
            ->with('subscribes')
            ->get();

        if ($products->count() < 1) {
            $this->warn('Target not found');

            return Command::INVALID;
        }

        $count = 0;
        // 상품 별 구독중인 카드를 결제 시킴
        $products->each(function (WhaleProduct $product) use (&$count) {
            $subscribes = WhaleMemberSubscribeProduct::where('product_id', '=', $product->id)
                ->where('is_activated', '=', true)
                ->where('is_started', '=', true)
                ->get();

            $subscribes->each(function (WhaleMemberSubscribeProduct $subscribeProduct) use (&$count) {
                ProductBillingPaymentJob::dispatch($subscribeProduct);
                $count++;
            });
        });

        $this->info("고래영어 정기결제: {$count}건 Job 생성 완료");

        return Command::SUCCESS;
    }
}
