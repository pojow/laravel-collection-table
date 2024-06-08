<?php

namespace Pojow\LaravelCollectionTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTable extends GeneratorCommand
{
    /** @var string */
    protected $type = 'Table';

    /** @var string */
    protected $name = 'make:table';

    /** @var string */
    protected $description = 'Create a new table configuration.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/table.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables';
    }
}
