<?php

namespace App\Console\Commands;

use App\Jobs\ProductBillingPaymentJob;
use App\Models\MemberSubscribeProduct;
use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SubscribeProductPaymentScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscribe-product-payment-schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '정기결제를 실행합니다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->day;
        $productService = app(ProductServiceInterface::class);

        // 정기 결제일이 오늘인 상품 검색
        $products = Product::where('payment_day', '=', $today)
            ->whereHas('subscribes', function (Builder $query) {
                $query->whereHas('member');
            })
            ->get();

        if ($products->count() < 1) {
            $this->warn('Target not found');
            return Command::INVALID;
        }

        // 상품 별 구독중인 카드를 결제 시킴
        $products->each(function (Product $product) use ($productService) {
            $subscribes = $productService->getSubscribes($product);
            $subscribes->each(fn(MemberSubscribeProduct $subscribeProduct) => ProductBillingPaymentJob::dispatch($subscribeProduct));
        });

        $this->info('Okay');
        return Command::SUCCESS;
    }
}
