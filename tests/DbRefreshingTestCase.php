<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DbRefreshingTestCase extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }
}
