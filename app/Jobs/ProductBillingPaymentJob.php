<?php

namespace App\Jobs;

use App\Models\MemberSubscribeProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductBillingPaymentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MemberSubscribeProduct $subscribeProduct
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO : 결제 요청

    }
}
