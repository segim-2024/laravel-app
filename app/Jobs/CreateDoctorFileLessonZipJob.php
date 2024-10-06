<?php

namespace App\Jobs;

use App\Models\DoctorFileLesson;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;
use App\Services\Interfaces\FileServiceInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateDoctorFileLessonZipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $tries = 1;
    protected int $maxExceptions = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public DoctorFileLesson $lesson
    )
    {
        $this->onQueue('zip');
    }

    /**
     * Execute the job.
     */
    public function handle(
        DoctorFileLessonServiceInterface $service,
        FileServiceInterface $fileService,
    ): void
    {
        if ($this->lesson->materials->count() < 2) {
            return;
        }

        if ($this->lesson->zip) {
            $fileService->delete($this->lesson->zip);
        }

        try {
            $service->createZip($this->lesson);
        } catch (Exception $exception) {
            $this->fail($exception);
            Log::error($exception);
        }
    }
}
