<?php

namespace Msemeen\ErrorReporter\Contracts;

use Msemeen\ErrorReporter\Support\ErrorMessage;

interface ChannelInterface
{
    public function send(ErrorMessage $errorMessage): void;
}