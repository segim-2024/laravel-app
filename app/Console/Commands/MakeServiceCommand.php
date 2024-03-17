<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name')."Service";

        try {
            $this->createInterface($name);
            $this->createService($name);
            $this->bindService($name);
            $this->info('Service created successfully.');
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());
        }
    }

    protected function createInterface($name): void
    {
        $filePath = app_path("Services/Interfaces/{$name}Interface.php");
        if (File::exists($filePath)) {
            throw new RuntimeException("{$name}Interface Class already exists!");
        }

        $stub = str_replace('{{class}}', $name, $this->getServiceInterfaceStub());

        $path = app_path('Services/Interfaces');
        if (! File::exists($path)) {
            File::makeDirectory($path, 0777,true, true);
        }

        File::put($filePath, $stub);
    }

    protected function getServiceInterfaceStub():string
    {
        return file_get_contents(base_path('app/Console/Commands/stubs/service_interface.stub'));
    }

    protected function createService($name): void
    {
        $filePath = app_path("Services/{$name}.php");
        if (File::exists($filePath)) {
            throw new RuntimeException("{$name} Class already exists!");
        }

        $stub = str_replace('{{class}}', $name, $this->getServiceStub());
        File::put($filePath, $stub);
    }

    protected function getServiceStub():string
    {
        return file_get_contents(base_path('app/Console/Commands/stubs/service.stub'));
    }

    protected function bindService($name): void
    {
        $providerPath = app_path('Providers/ServicesServiceProvider.php');
        $providerContents = file_get_contents($providerPath);
        $binding = "\$this->app->bind(\\App\\Services\\Interfaces\\{$name}Interface::class, \\App\\Services\\{$name}::class);";

        // Add binding to the end of the register method
        if (!Str::contains($providerContents, $binding)) {
            // register 메소드의 마지막 부분을 찾아 바인딩을 추가합니다.
            $providerContents = preg_replace(
                '/(public function register\(\)[\s\S]*?)(\}\s*\n\s*public function boot\(\))/',
                "$1    $binding\n    $2",
                $providerContents
            );

            file_put_contents($providerPath, $providerContents);
        }
    }
}
