<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents;

use Illuminate\Support\ServiceProvider;
use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use RuntimeException;

class MjmlBackendComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // php artisan vendor:publish --tag=mjml-backend-components-config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mjml-backend-components.php' => config_path('mjml-backend-components.php'),
            ], 'mjml-backend-components-config');
        }
    }

    public function register(): void
    {
        // Fallback to internal config settings smoothly
        $this->mergeConfigFrom(__DIR__.'/../config/mjml-backend-components.php', 'mjml-backend-components');

        // Bind the interface dynamically using the package-specific config prefix
        $this->app->singleton(CompilesMjml::class, function ($app) {
            $defaultDriver = config('mjml-backend-components.default');
            $driverConfig = config("mjml-backend-components.drivers.{$defaultDriver}");

            if (! $driverConfig || ! isset($driverConfig['class'])) {
                throw new RuntimeException("MJML compiler driver [{$defaultDriver}] is not configured correctly.");
            }

            $compilerClass = $driverConfig['class'];

            // Instantiate and inject the individual driver's config block
            return new $compilerClass($driverConfig);
        });
    }
}
