<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        
        // Key generation removed - use fixed key from .env.testing
    }

    protected function assertRedirectContains($response, $needle)
    {
        $this->assertTrue(
            str_contains($response->headers->get('Location'), $needle),
            "Expected redirect URL to contain '{$needle}', but got: " . $response->headers->get('Location')
        );
        
        return $this;
    }
}
