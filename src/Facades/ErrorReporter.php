<?php

namespace Msemeen\ErrorReporter\Facades;

use Illuminate\Support\Facades\Facade;

class ErrorReporter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'error-reporter';
    }
}