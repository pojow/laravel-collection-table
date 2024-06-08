<?php

namespace Pojow\LaravelCollectionTable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pojow\LaravelCollectionTable\LaravelCollectionTableServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelCollectionTableServiceProvider::class,
        ];
    }
}
