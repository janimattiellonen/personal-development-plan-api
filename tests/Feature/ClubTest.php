<?php

namespace Tests\Feature;

use Tests\DbRefreshingTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClubTest extends DbRefreshingTestCase
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
    public function assertClubIsCreated()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/clubs', [
            'name' => 'Club 1',
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
    public function assertClubIsNotCreatedDueToMissingValues()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/clubs', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'isActive']);
    }

    /**
     * @test
     */
    public function assertClubsIsNotCreatedDueToDuplicateClubName()
    {
        $this->refreshDatabase();

        $this->post('/api/admin/clubs', [
            'name' => 'Club 1',
            'isActive' => 1
        ]);
        $response = $this->post('/api/admin/clubs', [
            'name' => 'Club 1',
            'isActive' => 1
        ]);

        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'Could not create club with the given name, as a different club with the same name already exists'
            ]
        );
    }

    /**
     * @test
     */
    public function assertClubIsRemoved()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/clubs', [
            'name' => 'Club 1',
            'isActive' => 1
        ]);

        $response->assertJson(
            [
                'status' => true,
                'id' => 1
            ]
        );

        $json = $response->json();

        $id = $json['id'];

        $response = $this->delete("/api/admin/clubs/$id");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function assertDeletingNonExistingClubReturnsExpectedResult()
    {
        $this->refreshDatabase();
        $response = $this->delete("/api/admin/clubs/1");

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function assertExpectedResultWhenTryingToRemoveANonExistingClub()
    {
        $this->refreshDatabase();

        $response = $this->delete("/api/admin/clubs/46565");

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function assertCannotRenameClub()
    {
        $this->refreshDatabase();

        $response = $this->post('/api/admin/clubs', [
            'name' => 'Club 1',
            'isActive' => 1
        ]);

        $response->assertJson(
            [
                'status' => true,
                'id' => 1
            ]
        );

        $response = $this->post('/api/admin/clubs', [
            'name' => 'Club 2',
            'isActive' => 1
        ]);

        $response->assertJson(
            [
                'status' => true,
                'id' => 2
            ]
        );

        $response = $this->put('/api/admin/clubs/1', [
            'name' => 'Club 2',
            'isActive' => 1
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function assertClubNotFound()
    {
        $response = $this->get('/api/admin/clubs/1');

        $response->assertStatus(404);
    }
}
