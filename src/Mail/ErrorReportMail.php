<?php
namespace Msemeen\ErrorReporter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Msemeen\ErrorReporter\Support\ErrorMessage;

class ErrorReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ErrorMessage $error)
    {
    }

    public function build()
    {
        return $this->subject($this->error->getTitle() ?: 'Error Report')
            ->markdown('error-reporter::error-template')
            ->with(['error' => $this->error]);
    }
}