<?php

namespace Bleuren\LaravelApi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name}';

    protected $description = 'Create a new repository class';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $this->createRepository($name);
        $this->createContract($name);
        $this->updateAppServiceProvider($name);
    }

    protected function createRepository($name)
    {
        $repositoryTemplate = str_replace(
            ['{{repositoryName}}', '{{modelName}}'],
            [$name, Str::singular($name)],
            $this->getStub('Repository')
        );

        $path = app_path("Repositories/{$name}Repository.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $repositoryTemplate);
        $this->info('Repository created successfully.');
    }

    protected function createContract($name)
    {
        $contractTemplate = str_replace(
            ['{{repositoryName}}'],
            [$name],
            $this->getStub('RepositoryContract')
        );

        $path = app_path("Contracts/{$name}RepositoryInterface.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $contractTemplate);
        $this->info('Repository Contract created successfully.');
    }

    protected function updateAppServiceProvider($name)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $content = $this->files->get($providerPath);

        $bindingCode = "\$this->app->bind(\\App\\Contracts\\{$name}RepositoryInterface::class, \\App\\Repositories\\{$name}Repository::class);";

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
