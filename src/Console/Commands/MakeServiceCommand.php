<?php

namespace Bleuren\LaravelApi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';

    protected $description = 'Create a new service class';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $this->createService($name);
        $this->createContract($name);
        $this->updateAppServiceProvider($name);
    }

    protected function createService($name)
    {
        $serviceTemplate = str_replace(
            ['{{serviceName}}', '{{repositoryName}}'],
            [$name, $name],
            $this->getStub('Service')
        );

        $path = app_path("Services/{$name}Service.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $serviceTemplate);
        $this->info('Service created successfully.');
    }

    protected function createContract($name)
    {
        $contractTemplate = str_replace(
            ['{{serviceName}}'],
            [$name],
            $this->getStub('ServiceContract')
        );

        $path = app_path("Contracts/{$name}ServiceInterface.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $contractTemplate);
        $this->info('Service Contract created successfully.');
    }

    protected function updateAppServiceProvider($name)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $content = $this->files->get($providerPath);

        $bindingCode = "\$this->app->bind(\\App\\Contracts\\{$name}ServiceInterface::class, \\App\\Services\\{$name}Service::class);";

        if (Str::contains($content, 'public function register()')) {
            $content = str_replace(
                'public function register()',
                "public function register()\n    {\n        $bindingCode",
                $content
            );
        } else {
            $content .= "\n\n    public function register()\n    {\n        $bindingCode\n    }";
        }

        $this->files->put($providerPath, $content);
        $this->info('AppServiceProvider updated successfully.');
    }

    protected function getStub($type)
    {
        return $this->files->get(__DIR__."/stubs/$type.stub");
    }

    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
    }
}
