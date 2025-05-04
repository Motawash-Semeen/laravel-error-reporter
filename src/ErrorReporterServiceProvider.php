<?php

namespace Msemeen\ErrorReporter;

use Illuminate\Support\ServiceProvider;

class ErrorReporterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/error-reporter.php', 'error-reporter'
        );

        $this->app->singleton('error-reporter', function ($app) {
            return new ErrorReporter($app);
        });
    }

    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'error-reporter');

        // Publish configurations
        $this->publishes([
            __DIR__.'/../config/error-reporter.php' => config_path('error-reporter.php'),
        ], 'error-reporter-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/error-reporter'),
        ], 'error-reporter-views');
    }
}