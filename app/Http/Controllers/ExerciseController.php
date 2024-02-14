<?php

namespace App\Http\Controllers;

use App\Services\ExerciseService;
use Illuminate\Http\Request;
use App\Http\Requests\ExerciseRequest;

class ExerciseController
{
    public function create(ExerciseRequest $request, ExerciseService $exerciseService)
    {
        $data = $request->post();

        $exerciseId = $exerciseService->createExercise($data);

        return response()->json(['status' => true, 'id' => $exerciseId], 201);
    }

    public function update(int $id, ExerciseRequest $request, ExerciseService $exerciseService)
    {
        $data = $request->post();

        $exerciseService->updateExercise($id, $data);

        return response()->json(['status' => true, 'id' => $id], 201);
    }

    public function remove(int $id, ExerciseService $exerciseService)
    {
        $status = $exerciseService->removeExercise($id);

        return response()->json(['status' => true], $status ? 200 : 404);
    }

    public function getExercise(int $id, ExerciseService $exerciseService)
    {
        $exercise = $exerciseService->getExercise($id);

        return response()->json(['status' => true, 'data' => $exercise], 200);
    }

    public function getExercises(ExerciseService $exerciseService)
    {
        $data = $exerciseService->getExercises();

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
