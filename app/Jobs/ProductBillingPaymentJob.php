<?php

namespace App\Jobs;

use App\DTOs\CreateMemberPaymentDTO;
use App\Models\MemberSubscribeProduct;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\TossServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        $subscribeProduct = $this->subscribeProduct;
        $paymentService = app(MemberPaymentServiceInterface::class);
        $tossService = app(TossServiceInterface::class);

        Log::info(1111);
        $payment = $paymentService->save(
            CreateMemberPaymentDTO::createFromMemberSubscribe($subscribeProduct)
        );

        Log::info($payment->jsonSerialize());

        $response = $tossService->requestBillingPayment($payment, $subscribeProduct);
        $paymentService->process($payment, $response);
    }
}
