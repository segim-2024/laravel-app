<?php

namespace App\Jobs;

use App\DTOs\CreateMemberPaymentDTO;
use App\Models\MemberSubscribeProduct;
use App\Models\WhaleMemberSubscribeProduct;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use App\Services\Interfaces\PortOneServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProductBillingPaymentJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 60;

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        // 파머스/고래 구분을 위해 prefix 추가
        $prefix = $this->subscribeProduct instanceof WhaleMemberSubscribeProduct ? 'whale' : 'segim';

        return $prefix.'-'.$this->subscribeProduct->getId();
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MemberSubscribeProduct|WhaleMemberSubscribeProduct $subscribeProduct
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribeProduct = $this->subscribeProduct;
        $paymentService = app(MemberPaymentServiceInterface::class);
        $portOneService = app(PortOneServiceInterface::class);

        // 결제 생성
        $payment = $paymentService->save(CreateMemberPaymentDTO::createFromSubscribe($subscribeProduct));

        // 결제 요청
        try {
            $portOneService->requestPaymentByBillingKey($subscribeProduct->card->key, $payment);
        } catch (Exception $e) {
            $paymentService->manuallySetFailed($payment, $e->getMessage());
            Log::error($e);
        }
    }
}
