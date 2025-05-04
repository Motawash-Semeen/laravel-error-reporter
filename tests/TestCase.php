<?php

namespace Msemeen\ErrorReporter\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;

class TestCase extends BaseTestCase
{
    protected Application $app;
    protected Config $config;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->createApplication();
        $this->setUpConfig();
    }

    protected function createApplication(): void
    {
        $this->app = new Application(__DIR__.'/../');
        $this->app->singleton('events', fn() => new Dispatcher());
    }

    protected function setUpConfig(): void
    {
        $this->config = new Config([
            'error-reporter' => [
                'channels' => [
                    'discord' => [
                        'enabled' => true,
                        'webhook_url' => 'https://discord.example.com/webhook',
                        'username' => 'Error Bot'
                    ],
                    'email' => [
                        'enabled' => true,
                        'to' => 'admin@example.com'
                    ],
                    'slack' => [
                        'enabled' => true,
                        'webhook_url' => 'https://slack.example.com/webhook',
                        'username' => 'Error Reporter'
                    ]
                ]
            ],
            'mail' => [
                'default' => 'array'
            ]
        ]);

        $this->app->instance('config', $this->config);
    }

    protected function tearDown(): void
    {
        unset($this->app);
        parent::tearDown();
    }
}