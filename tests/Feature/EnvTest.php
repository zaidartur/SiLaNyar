<?php

// tests/Feature/EnvTest.php
namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnvTest extends TestCase
{
    public function testCorrectDatabaseUsed()
    {
        dump([
            'env' => app()->environment(),
            'db' => DB::connection()->getDatabaseName(),
        ]);

        $this->assertEquals('silanyar_testing', DB::connection()->getDatabaseName());
    }
}
