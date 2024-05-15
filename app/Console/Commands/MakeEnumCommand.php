<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEnumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Enum class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $className = $name . 'Enum';
        $path = app_path("Enums/{$className}.php");

        $this->createEnum($path, $className);
        $this->info('Enum Class created successfully!');
    }


    protected function createEnum($path, $className)
    {
        if (File::exists($path)) {
            $this->error('Enum already exists!');
            return;
        }

        $stub = $this->getStub();
        $stub = str_replace('{{class}}', $className, $stub);

        File::put($path, $stub);
    }

    protected function getStub()
    {
        return file_get_contents(app_path('Console/Commands/stubs/enum.stub'));
    }
}
