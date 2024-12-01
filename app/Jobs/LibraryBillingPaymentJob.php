<?php

namespace App\Jobs;

use App\DTOs\CreateMemberPaymentDTO;
use App\Services\Interfaces\LibraryProductSubscribeServiceInterface;
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

class LibraryBillingPaymentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $subscribeId
    ) {}

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public int $uniqueFor = 10;


    /**
     * The unique ID of the job.
     *
     * @return integer
     */
    public function uniqueId(): int
    {
        return $this->subscribeId;
    }

    /**
     * Execute the job.
     */
    public function handle(
        LibraryProductSubscribeServiceInterface $subscribeService,
        MemberPaymentServiceInterface $paymentService,
        PortOneServiceInterface $portOneService
    ): void
    {
        $subscribe = $subscribeService->findById($this->subscribeId);
        if (! $subscribe) {
            return;
        }

        // 결재 생성
        $payment = $paymentService->save(CreateMemberPaymentDTO::createFromLibrarySubscribe($subscribe));

        // 결제 요청
        try {
            $portOneService->requestPaymentByBillingKey($subscribe->card->key, $payment);
        } catch (Exception $e) {
            $paymentService->manuallySetFailed($payment, $e->getMessage());
            Log::error($e);
        }
    }
}
