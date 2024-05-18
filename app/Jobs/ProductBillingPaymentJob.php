<?php

namespace App\Jobs;

use App\DTOs\CreateMemberPaymentDTO;
use App\Models\MemberSubscribeProduct;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\TossServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductBillingPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MemberSubscribeProduct $subscribeProduct
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribeProduct = $this->subscribeProduct;
        $paymentService = app(MemberPaymentServiceInterface::class);
        $tossService = app(TossServiceInterface::class);

        // 결재 생성
        $payment = $paymentService->save(
            CreateMemberPaymentDTO::createFromMemberSubscribe($subscribeProduct)
        );

        // 결제 요청
        $response = $tossService->requestBillingPayment($payment, $subscribeProduct);

        // 결제 처리
        DB::beginTransaction();
        try {
            $paymentService->process($payment, $response);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
