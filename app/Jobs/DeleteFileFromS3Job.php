<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\Interfaces\FileServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteFileFromS3Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public File $file
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(FileServiceInterface::class)->delete($this->file);
    }
}
