<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @test
     */
    public function assertExerciseIsRemovedFromTrainingSession()
    {
        $this->refreshDatabase();
        $this->seed();

        $developmentPlanId = DB::table('personal_plans')->insertGetId([
            'name' => 'Development Plan 1',
            'is_active' => 1,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-12-31 23:59:59',
            'goals' => 'Development Plan 1 goals',
            'student_id' => 1,
            'instructor_id' => 2,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $trainingSessionId = DB::table('training_sessions')->insertGetId([
            'name' => 'Training Session 1',
            'is_active' => 1,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-12-31 23:59:59',
            'personal_plan_id' => $developmentPlanId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $exerciseId = DB::table('exercises')->insertGetId([
            'name' => 'Exercise 1',
            'description' => 'Exercise 1 description',
            'instructions' => 'Exercise 1 instructions',
            'url' => 'http://exercise1.com',
            'youtube_url' => 'http://youtube.com/exercise1',
            'is_active' => 1,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $trainingSessionExerciseId = DB::table('training_session_exercises')->insertGetId([
           'training_session_id' => $trainingSessionId,
            'exercise_id' => $exerciseId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);
        $this->assertDatabaseHas('training_session_exercises', [
            'id' => $trainingSessionExerciseId
        ]);

        $response = $this->delete(sprintf('/api/admin/training-sessions/%d/exercises/%d', $trainingSessionId, $exerciseId));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('training_session_exercises', [
            'id' => $trainingSessionExerciseId
        ]);
    }

    /**
     * @test
     */
    public function assertOnlyAvailableExercisesAreReturned()
    {
        $this->refreshDatabase();
        $this->seed();

        $developmentPlanId = DB::table('personal_plans')->insertGetId([
            'name' => 'Development Plan 1',
            'is_active' => 1,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-12-31 23:59:59',
            'goals' => 'Development Plan 1 goals',
            'student_id' => 1,
            'instructor_id' => 2,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $trainingSessionId = DB::table('training_sessions')->insertGetId([
            'name' => 'Training Session 1',
            'is_active' => 1,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-12-31 23:59:59',
            'personal_plan_id' => $developmentPlanId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $exerciseId = DB::table('exercises')->insertGetId([
            'name' => 'Exercise 1',
            'description' => 'Exercise 1 description',
            'instructions' => 'Exercise 1 instructions',
            'url' => 'http://exercise1.com',
            'youtube_url' => 'http://youtube.com/exercise1',
            'is_active' => 1,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $exercise2Id = DB::table('exercises')->insertGetId([
            'name' => 'Exercise 2',
            'description' => 'Exercise 2 description',
            'instructions' => 'Exercise 2 instructions',
            'url' => 'http://exercise2.com',
            'youtube_url' => 'http://youtube.com/exercise2',
            'is_active' => 1,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $exercise3Id = DB::table('exercises')->insertGetId([
            'name' => 'Exercise 3',
            'description' => 'Exercise 3 description',
            'instructions' => 'Exercise 3 instructions',
            'url' => 'http://exercise3.com',
            'youtube_url' => 'http://youtube.com/exercise3',
            'is_active' => 1,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $exercise4Id = DB::table('exercises')->insertGetId([
            'name' => 'Exercise 4',
            'description' => 'Exercise 4 description',
            'instructions' => 'Exercise 4 instructions',
            'url' => 'http://exercise4.com',
            'youtube_url' => 'http://youtube.com/exercise4',
            'is_active' => 1,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('training_session_exercises')->insertGetId([
            'training_session_id' => $trainingSessionId,
            'exercise_id' => $exerciseId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('training_session_exercises')->insertGetId([
            'training_session_id' => $trainingSessionId,
            'exercise_id' => $exercise2Id,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $response = $this->get(sprintf('/api/admin/training-sessions/%d/available-exercises', $trainingSessionId));

        $this->assertArrayHasKey('data', $response->json());

        $data = $response->json('data');

        $this->assertCount(2, $data);

        $ids = array_map(
            function ($exercise) {
                return $exercise['id'];
            },
            $data
        );

        $this->assertNotContains($exerciseId, $ids);
        $this->assertNotContains($exercise2Id, $ids);
        $this->assertContains($exercise3Id, $ids);
        $this->assertContains($exercise4Id, $ids);
    }
}
