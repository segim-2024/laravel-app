<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\Models\MemberPayment;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendLibrarySubscribeCanceledTokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MemberPayment $payment
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AlimTokClientServiceInterface $service
    ): void
    {
        if (! $this->payment->member->mb_hp) {
            return;
        }

        $content = view('toks.library_subscribe_canceled', [
            'productName' => $this->payment->productable->name,
        ])->render();

        $attachments = [
            'button' => [
                [
                    'name' => "결제 신청 바로가기",
                    'type' => "WL",
                    'url_pc' => 'http://epamus.com/bbs/login.php?url=%2Fapi%2Fe_cash_sso.php',
                    'url_mobile' => 'http://epamus.com/bbs/login.php?url=%2Fapi%2Fe_cash_sso.php',
                    'target' => "out",
                ]
            ]
        ];

        try {
            $response = $service->send(new AlimTokDTO(
                phoneNumber: $this->payment->member->mb_hp,
                templateCode: 'ecash005',
                message: $content,
                attachments: $attachments
            ));

            Log::warning(json_encode($response));
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
