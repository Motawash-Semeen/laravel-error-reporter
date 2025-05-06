<?php

namespace Msemeen\ErrorReporter\Tests;

use Msemeen\ErrorReporter\ErrorReporter;
use Msemeen\ErrorReporter\Exceptions\InvalidChannelException;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ErrorReporterTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function test_it_initializes_channels_from_config()
    {
        $config = [
            'error-reporter' => [
                'channels' => [
                    'discord' => ['enabled' => true, 'webhook' => 'test'],
                    'email' => ['enabled' => false],
                    'slack' => ['enabled' => true, 'webhook' => 'test'],
                ]
            ]
        ];

        $app = ['config' => $config];
        $reporter = new ErrorReporter($app);

        $this->assertCount(2, $reporter->getChannels());
    }

    public function test_it_throws_exception_for_invalid_channel()
    {
        $this->expectException(InvalidChannelException::class);

        $config = [
            'error-reporter' => [
                'channels' => [
                    'invalid' => ['enabled' => true]
                ]
            ]
        ];

        $app = ['config' => $config];
        new ErrorReporter($app);
    }

    public function test_report_method_creates_error_message()
    {
        $app = ['config' => ['error-reporter' => ['channels' => []]]];
        $reporter = new ErrorReporter($app);

        $mockChannel = m::mock('Msemeen\ErrorReporter\Contracts\ChannelInterface');
        $mockChannel->shouldReceive('send')->once();

        $reflection = new \ReflectionClass($reporter);
        $property = $reflection->getProperty('channels');
        $property->setAccessible(true);
        $property->setValue($reporter, ['discord' => $mockChannel]);

        $exception = new \Exception('Test exception');
        $reporter->report($exception);
    }

    public function test_send_method_creates_custom_message()
    {
        $app = ['config' => ['error-reporter' => ['channels' => []]]];
        $reporter = new ErrorReporter($app);

        $mockChannel = m::mock('Msemeen\ErrorReporter\Contracts\ChannelInterface');
        $mockChannel->shouldReceive('send')->once();

        $reflection = new \ReflectionClass($reporter);
        $property = $reflection->getProperty('channels');
        $property->setAccessible(true);
        $property->setValue($reporter, ['discord' => $mockChannel]);

        $reporter->send('Test message', 'Test title');
    }

    public function test_channel_method_returns_channel_instance()
    {
        $config = [
            'error-reporter' => [
                'channels' => [
                    'discord' => ['enabled' => true, 'webhook' => 'test']
                ]
            ]
        ];

        $app = ['config' => $config];
        $reporter = new ErrorReporter($app);

        $channel = $reporter->channel('discord');
        $this->assertNotNull($channel);
    }

    public function test_channel_method_returns_null_for_invalid_channel()
    {
        $config = [
            'error-reporter' => [
                'channels' => [
                    'discord' => ['enabled' => true, 'webhook' => 'test']
                ]
            ]
        ];

        $app = ['config' => $config];
        $reporter = new ErrorReporter($app);

        $this->assertNull($reporter->channel('nonexistent'));
    }
}