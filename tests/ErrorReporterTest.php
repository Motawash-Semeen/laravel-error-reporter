<?php

namespace Msemeen\ErrorReporter\Tests;

use PHPUnit\Framework\TestCase;
use Msemeen\ErrorReporter\Facades\ErrorReporter;
use Msemeen\ErrorReporter\ErrorReporterServiceProvider;
use Illuminate\Foundation\Application;

class ErrorReporterTest extends TestCase
{
    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app = new Application(__DIR__.'/../');
        $this->app->register(ErrorReporterServiceProvider::class);
    }

    /** @test */
    public function it_can_initialize_the_facade()
    {
        $instance = ErrorReporter::getFacadeRoot();
        $this->assertNotNull($instance);
    }

    /** @test */
    public function it_registers_the_service_provider()
    {
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(ErrorReporterServiceProvider::class, $providers);
    }
}