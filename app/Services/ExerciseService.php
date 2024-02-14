<?php

namespace App\Services;

use App\Services\Exception\RecordNotFoundException;
use Illuminate\Support\Facades\DB;

class ExerciseService
{
    public function createExercise(array $data): int
    {
        $exercise = ExerciseMapper::toDTO($data);

        $now = new \DateTime();
        $exercise['created_at'] = $now;
        $exercise['updated_at'] = $now;

        $exerciseSanitized = ExerciseMapper::sanitizeData($exercise, AbstractMapper::OPERATION_CREATE);

        return DB::table('exercises')->insertGetId(
            $exerciseSanitized
        );
    }

    public function updateExercise(int $id, array $data): int
    {
        $exercise = ExerciseMapper::toDTO($data);

        $now = new \DateTime();
        $exercise['updated_at'] = $now;

        $exerciseSanitized = ExerciseMapper::sanitizeData($exercise, AbstractMapper::OPERATION_UPDATE);

        $result = DB::table('exercises')
            ->where('id', $id)
            ->update($exerciseSanitized);

        if ($result === 0) {
            throw new RecordNotFoundException(sprintf('Could not update exercise with the given id %d, as no exercise with that id exists', $id));
        }

        return $result;
    }

    public function getExercise(int $id)
    {
        $result = DB::table('exercises')
            ->orderBy('name', 'ASC')
            ->find(
                $id,
                ['id', 'name', 'description','instructions', 'url', 'youtube_url', 'is_active']
            );

        if (!$result) {
            throw new RecordNotFoundException(sprintf('Could not find exercise with the given id %d', $id));
        }

        return ExerciseMapper::fromDTO((object)$result);
    }

    public function getExercises()
    {
        $result = DB::table('exercises')
            ->orderBy('name', 'ASC')
            ->get(['id', 'name', 'description','instructions', 'url', 'youtube_url', 'is_active']);


        return array_values(
            array_map(
                function ($exercise) {
                    return ExerciseMapper::fromDTO((object)$exercise);
                },
                $result->toArray()
            )
        );
    }

    public function removeExercise(int $id): bool
    {
        return DB::table('exercises')->delete($id) === 1;
    }
}
