<?php

namespace App\Jobs;

use App\DTOs\AlimTokDTO;
use App\Models\Member;
use App\Services\Interfaces\AlimTokClientServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartSubscribeSendAlimTokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Member $member
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! $this->member->mb_hp) {
            return;
        }

        $phone = str_replace('-', '', $this->member->mb_hp);
        $content = view('toks.start_subscribe', [
            'memberSchoolName' => $this->member->mb_nick,
        ])->render();

        $DTO = new AlimTokDTO(
            phoneNumber: $phone,
            templateCode: 'ecash001',
            message: $content,
        );

        app(AlimTokClientServiceInterface::class)->send($DTO);
    }
}
