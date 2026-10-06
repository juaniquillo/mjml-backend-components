<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Tests;

use Juaniquillo\BackendComponents\BackendComponentsServiceProvider as BaseBackendComponentsServiceProvider;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponentsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BaseBackendComponentsServiceProvider::class,
            MjmlBackendComponentsServiceProvider::class,
        ];
    }
}
