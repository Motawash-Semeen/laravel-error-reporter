<?php

namespace Msemeen\ErrorReporter\Channels;

use Msemeen\ErrorReporter\Contracts\ChannelInterface;
use Msemeen\ErrorReporter\Support\ErrorMessage;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class EmailChannel implements ChannelInterface
{
    public function __construct(protected array $config)
    {
    }

    public function send(ErrorMessage $errorMessage): void
    {
        if (empty($this->config['to'])) {
            return;
        }

        $mailer = app(Mailer::class);

        $mailer->send('error-reporter::error-template', [
            'error' => $errorMessage,
        ], function ($message) use ($errorMessage) {
            $message->to($this->config['to'])
                ->subject($errorMessage->getTitle() ?: 'Error Report');
        });
    }
}