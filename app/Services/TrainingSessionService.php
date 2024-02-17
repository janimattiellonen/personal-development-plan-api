<?php

namespace App\Services;

use App\Repositories\TrainingSessionRepository;
use App\Services\Exception\RecordNotFoundException;

class TrainingSessionService
{
    public function __construct(private TrainingSessionRepository $trainingSessionRepository) {}

    public function getTrainingSession(int $id)
    {
        $trainingSession =  $this->trainingSessionRepository->getTrainingSession($id);

        if (!$trainingSession) {
            throw new RecordNotFoundException(sprintf('Could not find training session with the given id %d', $id));
        }

        return $trainingSession;
    }

    public function removeExercise(int $trainingSessionId, int $exerciseId): int
    {
        return $this->trainingSessionRepository->removeExercise($trainingSessionId, $exerciseId);
    }

    public function getAvailableExercises(int $id)
    {
        $exercises = $this->trainingSessionRepository->getAvailableExercises($id);

        return array_map(
            function ($exercise) {
                return ExerciseMapper::fromDTO((object)$exercise);
            },
            $exercises->toArray()
        );
    }

    public function addExercise(int $trainingSessionId, int $exerciseId): int
    {
        return $this->trainingSessionRepository->addExercise($trainingSessionId, $exerciseId);
    }

}
