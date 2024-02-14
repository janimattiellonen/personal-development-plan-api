<?php

namespace Tests\Feature;

use Tests\DbRefreshingTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrainingSessionTest extends DbRefreshingTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->runDatabaseMigrations();
    }

    /**
     * @test
     */
    public function assertTrainingSessionIsCreated()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/exercises', [
            'name' => 'Exercise 1',
            'description' => 'Exercise 1 description',
            'instructions' => 'Exercise 1 instructions',
            'url' => 'http://exercise1.com',
            'youtubeUrl' => 'http://youtube.com/exercise1',
            'isActive' => 1
        ]);

        $response->assertStatus(201);

        $response->assertJson(
            [
                'status' => true,
                'id' => 1
            ]
        );
    }
}
