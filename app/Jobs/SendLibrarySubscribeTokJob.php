<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\Models\LibraryProductSubscribe;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendLibrarySubscribeTokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public LibraryProductSubscribe $subscribe
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AlimTokClientServiceInterface $service
    ): void
    {
        if (! $this->subscribe->member->mb_hp) {
            return;
        }

        $content = view('toks.library_subscribe', [
            'productName' => $this->subscribe->product->name,
            'paymentDay' => $this->subscribe->payment_day,
            'dueDate' => $this->subscribe->due_date->format('Y/m/d'),
        ])->render();

        $attachments = [
            'button' => [
                [
                    'name' => "구독서비스 관리 페이지 바로가기",
                    'type' => "WL",
                    'url_pc' => 'http://epamus.com/bbs/login.php?url=%2Fapi%2Fe_cash_sso.php',
                    'url_mobile' => 'http://epamus.com/bbs/login.php?url=%2Fapi%2Fe_cash_sso.php',
                    'target' => "out",
                ]
            ]
        ];

        try {
            $response = $service->send(new AlimTokDTO(
                phoneNumber: $this->subscribe->member->mb_hp,
                templateCode: 'ecash003',
                message: $content,
                attachments: $attachments
            ));

            Log::info(json_encode($response, JSON_THROW_ON_ERROR));
        } catch (Exception $e) {
            Log::error($e);
        }

    }
}
