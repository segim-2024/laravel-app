<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\DTOs\ShipmentTrackDTO;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Bus\Queueable;
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
        public ShipmentTrackDTO $DTO
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

        // 주문자의 핸드폰번호
        $phone = str_replace('-', '', $DTO->order->od_hp);
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

        $service->send(new AlimTokDTO(
            phoneNumber: $phone,
            templateCode: 'deliver01_2',
            message: $content,
            attachments: $attachments
        ));
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
