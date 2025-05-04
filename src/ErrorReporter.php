<?php

namespace Msemeen\ErrorReporter;

use Msemeen\ErrorReporter\Channels\DiscordChannel;
use Msemeen\ErrorReporter\Channels\EmailChannel;
use Msemeen\ErrorReporter\Channels\SlackChannel;
use Msemeen\ErrorReporter\Contracts\ChannelInterface;
use Msemeen\ErrorReporter\Enums\ChannelType;
use Msemeen\ErrorReporter\Exceptions\InvalidChannelException;
use Msemeen\ErrorReporter\Support\ErrorMessage;
use Throwable;

class ErrorReporter
{
    protected array $channels = [];

    public function __construct(protected $app)
    {
        $this->initializeChannels();
    }

    protected function initializeChannels(): void
    {
        $config = $this->app['config']['error-reporter'];

        foreach ($config['channels'] as $channel => $settings) {
            if ($settings['enabled'] ?? false) {
                $this->channels[$channel] = $this->createChannel($channel, $settings);
            }
        }
    }

    protected function createChannel(string $channel, array $settings): ChannelInterface
    {
        return match ($channel) {
            ChannelType::DISCORD->value => new DiscordChannel($settings),
            ChannelType::EMAIL->value => new EmailChannel($settings),
            ChannelType::SLACK->value => new SlackChannel($settings),
            default => throw new InvalidChannelException("Invalid channel: {$channel}"),
        };
    }

    public function report(Throwable $exception): void
    {
        $errorMessage = new ErrorMessage($exception);

        foreach ($this->channels as $channel) {
            $channel->send($errorMessage);
        }
    }

    public function send(string $message, ?string $title = null): void
    {
        $errorMessage = new ErrorMessage(null, $message, $title);

        foreach ($this->channels as $channel) {
            $channel->send($errorMessage);
        }
    }

    public function channel(string $channel): ?ChannelInterface
    {
        return $this->channels[$channel] ?? null;
    }
}