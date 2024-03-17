<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDTOCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DTO';

    public function handle()
    {
        $name = $this->argument('name');
        $className = $name . 'DTO';
        $path = app_path("DTOs/{$className}.php");

        $this->createDTO($path, $className);
        $this->info('DTO created successfully!');
    }

    protected function createDTO($path, $className)
    {
        if (File::exists($path)) {
            $this->error('DTO already exists!');
            return;
        }

        $stub = $this->getStub();
        $stub = str_replace('{{class}}', $className, $stub);

        File::put($path, $stub);
    }

    protected function getStub()
    {
        return file_get_contents(base_path('app/Console/Commands/stubs/dto.stub'));
    }
}
