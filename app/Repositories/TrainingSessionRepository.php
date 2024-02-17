<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use DateTime;

use App\Services\Exception\RecordNotFoundException;
use App\Services\Exception\RecordAlreadyExistsException;
use App\Services\TrainingSessionMapper;

class TrainingSessionRepository
{
    public function getTrainingSession(int $id)
    {
        $result = DB::table('training_sessions as ts')
            ->select(
                'ts.id as ts_id',
                'ts.name as ts_name',
                'ts.is_active as ts_is_active',
                'ts.personal_plan_id as ts_personal_plan_id',
                'ts.starts_at as ts_starts_at',
                'ts.ends_at as ts_ends_at',
                'e.id as e_id',
                'e.name as e_name',
                'e.description as e_description',
                'e.instructions as e_instructions',
                'e.is_active as e_is_active',
                'e.url as e_url',
                'e.youtube_url as e_youtube_url',
                'tse.training_session_id as tse_training_session_id'
            )
            ->leftJoin('training_session_exercises as tse', 'ts.id', '=', 'tse.training_session_id')
            ->leftJoin('exercises as e', 'e.id', '=', 'tse.exercise_id')
            ->where('ts.id', '=', $id)
            ->get();

        $hydrated = [];

        // this is copy-paste, must be refactored
        foreach ($result->toArray() as $row) {
            if (!isset($hydrated[$row->ts_id])) {
                $item = [
                    'id' => $row->ts_id,
                    'name' => $row->ts_name,
                    'is_active' => $row->ts_is_active,
                    'starts_at' => $row->ts_starts_at ? (new DateTime($row->ts_starts_at))->format('Y-m-d') : null,
                    'ends_at' => $row->ts_ends_at ? (new DateTime($row->ts_ends_at))->format('Y-m-d') : null,
                    'personal_plan_id' => $row->ts_personal_plan_id,
                ];
                $hydrated[$row->ts_id] = $item;
            }

            if (!empty($hydrated[$row->tse_training_session_id])) {
                $hydrated[$row->tse_training_session_id]['exercises'][] = [
                    'id' => $row->e_id,
                    'name' => $row->e_name,
                    'description' => $row->e_description,
                    'instructions' => $row->e_instructions,
                    'url' => $row->e_url,
                    'youtube_url' => $row->e_youtube_url,
                    'is_active' => $row->e_is_active,
                ];
            }
        }

        return count($hydrated) ? TrainingSessionMapper::fromDTO((object)array_values($hydrated)[0]) : [];
    }

    public function removeExercise(int $trainingSessionId, int $exerciseId): int
    {
        return DB::table('training_session_exercises')
            ->where('training_session_id', $trainingSessionId)
            ->where('exercise_id', $exerciseId)
            ->delete() === 1;
    }

    public function getAvailableExercises(int $id)
    {
        // fetch all exercises that are not part of the given training exercise
        // generate sql query

/*
    SELECT
        e.*
    FROM
        exercises e
    WHERE NOT EXISTS (
        SELECT 1
        FROM training_session_exercises tse
        WHERE tse.exercise_id = e.id
        AND tse.training_session_id = 47
    );
 */

       return DB::table('exercises as e')
           ->leftJoin('training_session_exercises as tse', function ($join) use ($id) {
               $join->on('e.id', '=', 'tse.exercise_id')
                   ->where('tse.training_session_id', '=', $id);
           })
           ->whereNull('tse.id')
           ->select('e.*')
           ->get();
    }

    public function addExercise(int $trainingSessionId, int $exerciseId): int
    {
        return DB::table('training_session_exercises')->insertGetId([
            'training_session_id' => $trainingSessionId,
            'exercise_id' => $exerciseId,
        ]);
    }
}
