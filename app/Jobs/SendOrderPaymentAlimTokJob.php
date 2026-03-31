<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\DTOs\OrderDTO;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderPaymentAlimTokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public OrderDTO $DTO
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $DTO = $this->DTO;
        $service = app(AlimTokClientServiceInterface::class);

        if (! $DTO->order->od_hp) {
            return;
        }

        $content = view('toks.order_payment', [
            'memberSchoolName' => $DTO->order->od_name,
            'orderNo' => $DTO->order->od_id,
            'orderPrice' => number_format($DTO->order->od_receipt_price),
        ])->render();

        $response = $service->send(new AlimTokDTO(
            phoneNumber: $DTO->order->od_hp,
            templateCode: 'deliver04',
            message: $content
        ));

        Log::info('[알림톡:결제완료] ' . json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Determine number of times the job may be attempted.
     */
    public function tries(): int
    {
        return 3;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addMinutes(5);
    }
}
