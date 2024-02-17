<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Services\TrainingSessionService;

class TrainingSessionController
{
    public function getTrainingSession(int $id, TrainingSessionService $trainingSessionService)
    {
        $trainingSession = $trainingSessionService->getTrainingSession($id);

        return response()->json(['status' => true, 'data' => $trainingSession], 200);
    }

    public function removeExercise(int $id, int $exerciseId, TrainingSessionService $trainingSessionService)
    {
        $status = $trainingSessionService->removeExercise($id, $exerciseId);

        return response()->json(['status' => true], $status ? 200 : 404);
    }

    public function getAvailableExercises(int $id, TrainingSessionService $trainingSessionService)
    {
        $result = $trainingSessionService->getAvailableExercises($id);

        return response()->json(['status' => true, 'data' => $result], 200);
    }

    public function addExercise(int $trainingSessionId, Request $request, TrainingSessionService $trainingSessionService)
    {
        $data = $request->post();

        $exerciseId = $data['exerciseId'];

        $trainingSessionExerciseId =  $trainingSessionService->addExercise($trainingSessionId, $exerciseId);

        return response()->json(['status' => true, 'trainingSessionExerciseId' => $trainingSessionExerciseId], 201);
    }
}
