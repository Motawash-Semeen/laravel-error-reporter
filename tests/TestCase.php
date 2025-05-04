<?php

namespace Msemeen\ErrorReporter\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Foundation\Application;

class TestCase extends BaseTestCase
{
    protected ?Application $app;

    protected function setUp(): void
    {
        parent::setUp(); // This makes setUp() available
        
        // Minimal Laravel app initialization
        if (class_exists(Application::class)) {
            $this->app = new Application(__DIR__.'/../');
            $this->app->register(\Msemeen\ErrorReporter\ErrorReporterServiceProvider::class);
        }
    }

    protected function tearDown(): void
    {
        $this->app = null;
        parent::tearDown();
    }
}