<?php

namespace Tests\Feature;

use Tests\DbRefreshingTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DevelopmentPlanTest extends DbRefreshingTestCase
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
    public function assertDevelopmentPlanIsCreated()
    {
        $this->refreshDatabase();
        $this->seed();

        $response = $this->post('/api/admin/development-plans', [
            'name' => 'Development plan 1',
            'startsAt' => '2024-01-05',
            'endsAt' => '2024-01-06',
            'goals' => 'Goals text',
            'studentId' => 1,
            'instructorId' => 2,
            'isActive' => 1,
            'trainingSessions' => [
                [
                    'name' => 'Session 1',
                    'startsAt' => '2024-01-05',
                    'endsAt' => '2024-01-06',
                    'isActive' => 1
                ]
            ]
        ]);

        $response->assertStatus(201);

        $response->assertJson(
            [
                'status' => true,
                'personal_plan_id' => 1,
            ]
        );
    }


    /**
     * @test-skip
     */
    public function assertDevelopmentPlanNotFound()
    {
        $response = $this->get('/api/admin/development-plans/1');

        $response->assertStatus(404);
    }
}
