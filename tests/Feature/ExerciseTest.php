<?php

namespace Tests\Feature;

use Tests\DbRefreshingTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExerciseTest extends DbRefreshingTestCase
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
    public function assertExerciseIsCreated()
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

    /**
     * @test
     */
    public function assertExerciseIsNotCreatedDueToMissingValues()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/exercises', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     */
    public function assertExerciseIsNotCreatedDueToTooShortName()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/exercises', ['name' => 'aa']);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     */
    public function assertExerciseNotFound()
    {
        $response = $this->get('/api/admin/exercises/1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function assertCannotRemoveNonExistingExercise()
    {
        $this->refreshDatabase();
        $response = $this->delete("/api/admin/exercises/1");

        $response->assertStatus(404);
    }
}
