<?php

namespace Msemeen\ErrorReporter\Support;

use Throwable;

class ErrorMessage
{
    public function __construct(
        protected ?Throwable $exception = null,
        protected ?string $message = null,
        protected ?string $title = null
    ) {
        if ($exception && !$message) {
            $this->message = $exception->getMessage();
        }

        if ($exception && !$title) {
            $this->title = get_class($exception);
        }
    }

    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function hasException(): bool
    {
        return $this->exception !== null;
    }
}