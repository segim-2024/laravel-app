<?php

namespace App\Jobs;

use App\DTOs\LibraryPaymentDoneApiRequestDTO;
use App\DTOs\UpdateLibraryPaymentApiLogDTO;
use App\Exceptions\PaymentNotFoundException;
use App\Services\Interfaces\LibraryApiServiceInterface;
use App\Services\Interfaces\LibraryPaymentApiLogServiceInterface;
use App\Services\Interfaces\MemberPaymentServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LibraryPaymentDoneApiJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->paymentId;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $paymentId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        MemberPaymentServiceInterface $paymentService,
        LibraryApiServiceInterface $apiService,
        LibraryPaymentApiLogServiceInterface $logService,
    ): void
    {
        try {
            $payment = $paymentService->findByKey($this->paymentId);
            if (! $payment) {
                throw new PaymentNotFoundException("결제 정보를 찾을 수 없습니다.");
            }

            $response = $apiService->paymentDone(LibraryPaymentDoneApiRequestDTO::createFromPaymentModel($payment));
            $logService->update(UpdateLibraryPaymentApiLogDTO::createFromPaymentAndResponseDTO($payment, $response));
        } catch (Exception $e) {
            Log::error($e);
            $this->fail($e);
        }
    }
}
