<?php

namespace Bleuren\LaravelApi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeApiCommand extends Command
{
    protected $signature = 'make:api {name} {--model} {--migration} {--resource}';

    protected $description = 'Create a complete API structure including Repository, Service, and Controller';

    public function handle()
    {
        $name = $this->argument('name');

        // Create Model if option is set
        if ($this->option('model')) {
            $this->call('make:model', ['name' => $name]);
        }

        // Create Migration if option is set
        if ($this->option('migration')) {
            $this->call('make:migration', ['name' => 'create_'.Str::plural(Str::snake($name)).'_table']);
        }

        // Create Repository
        $this->call('make:repository', ['name' => $name]);

        // Create Service
        $this->call('make:service', ['name' => $name]);

        // Create Controller
        $this->call('make:api-controller', ['name' => $name]);

        $this->info("API structure for {$name} created successfully!");

        if ($this->option('migration')) {
            $this->info("Don't forget to update and run the migration for the {$name} model.");
        }
    }
}
