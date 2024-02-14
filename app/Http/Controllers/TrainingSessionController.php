<?php

namespace App\Http\Controllers;

use App\Services\TrainingSessionService;

class TrainingSessionController
{
    public function getTrainingSession(int $id, TrainingSessionService $trainingSessionService)
    {
        $trainingSession = $trainingSessionService->getTrainingSession($id);

        return response()->json(['status' => true, 'data' => $trainingSession], 200);
    }
}
