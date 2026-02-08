<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $auditPrompt = '';

    protected function setUp(): void
    {
        parent::setUp();
        $path = base_path('prompt.txt');
        $this->auditPrompt = file_exists($path) ? (file_get_contents($path) ?: '') : '';
    }

    protected function getAuditPrompt(): string
    {
        return $this->auditPrompt;
    }
}
