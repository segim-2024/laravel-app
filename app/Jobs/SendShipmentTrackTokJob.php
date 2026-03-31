<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\DTOs\OrderDTO;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendShipmentTrackTokJob implements ShouldQueue
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

        $content = view('toks.shipment_track', [
            'memberSchoolName' => $DTO->order->od_name,
            'orderDate' => Carbon::parse($DTO->order->od_time)->format('Y-m-d H:i:s'),
            'orderNo' => $DTO->order->od_id
        ])->render();
        $link = "http://trace.cjlogistics.com/web/detail.jsp?slipno={$DTO->order->od_invoice}";
        $attachments = [
            'button' => [
                [
                    'name' => "배송 조회",
                    'type' => "WL",
                    'url_pc' => $link,
                    'url_mobile' => $link,
                    'target' => "out",
                ]
            ]
        ];

        $response = $service->send(new AlimTokDTO(
            phoneNumber: $DTO->order->od_hp,
            templateCode: 'deliver01_2',
            message: $content,
            attachments: $attachments
        ));

        Log::info('[알림톡:배송추적] ' . json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
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
