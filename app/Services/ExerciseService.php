<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ExerciseService
{
    public function createExercise(array $data): int
    {
        $now = new \DateTime();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        return DB::table('exercises')->insertGetId(
            $data
        );
    }

    public function updateExercise(int $id, array $data): int
    {
        $now = new \DateTime();
        $data['updated_at'] = $now;

        $result = DB::table('exercises')
            ->where('id', $id)
            ->update($data);

        if ($result === 0) {
            throw new NotFoundException(sprintf('Could not update exercise with the given id %d, as no exercise with that id exists', $id));
        }

        return $result;
    }

    public function getExercise(int $id)
    {
        return DB::table('exercises')
            ->orderBy('name', 'ASC')
            ->find(
                $id,
                ['id', 'name', 'description','instructions', 'url', 'youtube_url', 'is_active']
            );
    }

    public function getExercises()
    {
        return DB::table('exercises')
            ->orderBy('name', 'ASC')
            ->get(['id', 'name', 'description','instructions', 'url', 'youtube_url', 'is_active']);
    }

}
