<?php

namespace App\Jobs;

use App\Models\LibraryProductSubscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartLibraryProductSubscribeNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public LibraryProductSubscribe $subscribe,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if the user has a phone number

        // Send a notification to the user
    }
}
