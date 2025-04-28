<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Inertia\Testing\AssertableInertia as Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
