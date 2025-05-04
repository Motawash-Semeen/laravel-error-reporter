<?php

namespace Msemeen\ErrorReporter\Channels;

use Msemeen\ErrorReporter\Contracts\ChannelInterface;
use Msemeen\ErrorReporter\Support\ErrorMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DiscordChannel implements ChannelInterface
{
    protected Client $client;

    public function __construct(protected array $config)
    {
        $this->client = new Client();
    }

    public function send(ErrorMessage $errorMessage): void
    {
        if (empty($this->config['webhook_url'])) {
            return;
        }

        try {
            $this->client->post($this->config['webhook_url'], [
                'verify' => false,
                'json' => [
                    'content' => $this->formatMessage($errorMessage),
                    'username' => $this->config['username'] ?? 'Error Reporter',
                ],
            ]);
        } catch (GuzzleException $e) {
            // Silently fail to avoid infinite loops
            // dd('Discord webhook error: ' . $e->getMessage());
        }
    }

    protected function formatMessage(ErrorMessage $errorMessage): string
    {
        $title = $errorMessage->getTitle() ? "**{$errorMessage->getTitle()}**\n\n" : '';
        $message = $errorMessage->getMessage();

        if ($exception = $errorMessage->getException()) {
            $message .= "\n\n**File:** {$exception->getFile()}:{$exception->getLine()}";
            $message .= "\n\n**Stack Trace:**\n```\n{$exception->getTraceAsString()}\n```";
        }

        return $title . $message;
    }
}