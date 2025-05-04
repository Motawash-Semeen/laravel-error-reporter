<?php

namespace Msemeen\ErrorReporter\Tests\Unit;

use Msemeen\ErrorReporter\Tests\TestCase;
use Msemeen\ErrorReporter\ErrorReporter;
use Msemeen\ErrorReporter\Channels\DiscordChannel;
use Msemeen\ErrorReporter\Channels\EmailChannel;
use Msemeen\ErrorReporter\Channels\SlackChannel;
use Mockery;
use Exception;

class ErrorReporterTest extends TestCase
{
    /** @test */
    public function it_registers_channels_from_config()
    {
        $reporter = new ErrorReporter($this->app);
        
        $channels = $reporter->getChannels();
        
        $this->assertArrayHasKey('discord', $channels);
        $this->assertArrayHasKey('email', $channels);
        $this->assertArrayHasKey('slack', $channels);
        $this->assertInstanceOf(DiscordChannel::class, $channels['discord']);
    }

    /** @test */
    public function it_sends_errors_to_discord()
    {
        $mockDiscord = Mockery::mock(DiscordChannel::class);
        $mockDiscord->shouldReceive('send')->once();

        $reporter = new ErrorReporter($this->app);
        $reporter->setChannel('discord', $mockDiscord);

        $exception = new Exception('Test error');
        $reporter->report($exception);
    }

    /** @test */
    public function it_sends_custom_messages_to_slack()
    {
        $mockSlack = Mockery::mock(SlackChannel::class);
        $mockSlack->shouldReceive('send')->once()->withArgs(function ($message) {
            return $message->getMessage() === 'Custom error message';
        });

        $reporter = new ErrorReporter($this->app);
        $reporter->setChannel('slack', $mockSlack);

        $reporter->send('Custom error message', 'Alert');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}