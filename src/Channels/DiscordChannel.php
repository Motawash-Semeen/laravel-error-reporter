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
        $this->client = new Client([
            // Disable SSL verification for the Discord webhook
            'verify' => false,
        ]);
    }

    public function send(ErrorMessage $errorMessage): void
    {
        if (empty($this->config['webhook_url'])) {
            return;
        }

        try {
            if ($exception = $errorMessage->getException()) {
                $this->sendWithStackTrace($errorMessage);
            } else {
                $this->sendSimpleMessage($errorMessage);
            }
        } catch (GuzzleException $e) {
        }
    }

    protected function sendSimpleMessage(ErrorMessage $errorMessage): void
    {
        $this->client->post($this->config['webhook_url'], [
            'json' => [
                'content' => $this->formatMessage($errorMessage),
                'username' => $this->config['username'] ?? 'Error Reporter',
            ],
        ]);
    }

    protected function sendWithStackTrace(ErrorMessage $errorMessage): void
    {
        $exception = $errorMessage->getException();
        $title = $errorMessage->getTitle() ? "**{$errorMessage->getTitle()}**\n\n" : '';
        $message = $errorMessage->getMessage();
        $message .= "\n\n**File:** {$exception->getFile()}:{$exception->getLine()}";
        
        $tempFile = tempnam(sys_get_temp_dir(), 'error_');
        file_put_contents($tempFile, $exception->getTraceAsString());
        
        try {
            $this->client->post($this->config['webhook_url'], [
                'multipart' => [
                    [
                        'name' => 'content',
                        'contents' => substr($title . $message, 0, 2000)
                    ],
                    [
                        'name' => 'username',
                        'contents' => $this->config['username'] ?? 'Error Reporter'
                    ],
                    [
                        'name' => 'file',
                        'contents' => fopen($tempFile, 'r'),
                        'filename' => 'stacktrace.txt'
                    ]
                ]
            ]);
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    protected function formatMessage(ErrorMessage $errorMessage): string
    {
        $title = $errorMessage->getTitle() ? "**{$errorMessage->getTitle()}**\n\n" : '';
        $message = $errorMessage->getMessage();
        
        if ($exception = $errorMessage->getException()) {
            $message .= "\n\n**File:** {$exception->getFile()}:{$exception->getLine()}";
        }

        return substr($title . $message, 0, 2000); // Discord message length limit
    }
}