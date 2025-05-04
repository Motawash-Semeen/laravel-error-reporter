<?php

namespace Msemeen\ErrorReporter\Channels;

use Msemeen\ErrorReporter\Contracts\ChannelInterface;
use Msemeen\ErrorReporter\Support\ErrorMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SlackChannel implements ChannelInterface
{
    protected Client $client;

    public function __construct(protected array $config)
    {
        $this->client = new Client([
            'timeout' => 15,
            'connect_timeout' => 10,
        ]);
    }

    public function send(ErrorMessage $errorMessage): void
    {
        if (empty($this->config['webhook_url'])) {
            Log::warning('Slack webhook URL not configured');
            return;
        }

        try {
            $response = $this->client->post($this->config['webhook_url'], [
                'verify' => false,
                'json' => $this->buildPayload($errorMessage),
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('Slack API error: ' . $response->getBody());
            }
        } catch (GuzzleException $e) {
            Log::error('Failed to send Slack notification: ' . $e->getMessage());
        }
    }

    protected function buildPayload(ErrorMessage $errorMessage): array
    {
        $payload = [
            'text' => $this->formatMainMessage($errorMessage),
            'username' => $this->config['username'] ?? 'Error Reporter',
            'icon_emoji' => $this->config['icon_emoji'] ?? ':warning:',
            'attachments' => [
                [
                    'color' => '#ff0000',
                    'fields' => $this->buildAttachmentFields($errorMessage),
                    'footer' => config('app.name'),
                    'ts' => now()->timestamp,
                ]
            ]
        ];

        if ($errorMessage->hasException()) {
            $payload['attachments'][0]['text'] = "```" . $errorMessage->getException()->getTraceAsString() . "```";
        }

        return $payload;
    }

    protected function formatMainMessage(ErrorMessage $errorMessage): string
    {
        $title = $errorMessage->getTitle() ? "*{$errorMessage->getTitle()}*" : '';
        $message = $errorMessage->getMessage();

        if ($errorMessage->hasException()) {
            $exception = $errorMessage->getException();
            $message .= "\n*File:* {$exception->getFile()}:{$exception->getLine()}";
        }

        return trim("{$title}\n{$message}");
    }

    protected function buildAttachmentFields(ErrorMessage $errorMessage): array
    {
        $fields = [
            [
                'title' => 'Environment',
                'value' => config('app.env'),
                'short' => true,
            ],
            [
                'title' => 'URL',
                'value' => request()?->fullUrl() ?? 'N/A',
                'short' => true,
            ]
        ];

        if ($errorMessage->hasException()) {
            $fields[] = [
                'title' => 'Exception',
                'value' => get_class($errorMessage->getException()),
                'short' => true,
            ];
        }

        return $fields;
    }
}