<?php

namespace Msemeen\ErrorReporter\Tests;

use Msemeen\ErrorReporter\ErrorReporter;
use PHPUnit\Framework\TestCase;

class ErrorReporterTest extends TestCase
{
    /** @test */
    public function it_can_initialize()
    {
        $reporter = new ErrorReporter($this->createMock(\Illuminate\Contracts\Foundation\Application::class));
        $this->assertInstanceOf(ErrorReporter::class, $reporter);
    }
}