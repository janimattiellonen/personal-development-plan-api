<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use \DateTime;

class DevelopmentPlanService
{

    protected function buildAndSaveTrainingSessionData(array $trainingSessions, int $operation, int $personalPlanId): array
    {
        $now = new DateTime();

        return array_map(
            function ($trainingSessionData) use ($personalPlanId, $now, $operation) {

                $trainingSessionData['personalPlanId'] = $personalPlanId;
                $trainingSession = TrainingSessionMapper::toDTO($trainingSessionData);
                $trainingSession['created_at'] = $now;
                $trainingSession['updated_at'] = $now;

                $trainingSession['starts_at'] = new DateTime($trainingSession['starts_at']);

                if (!empty($trainingSession['ends_at'])) {
                    $trainingSession['ends_at'] = new DateTime($trainingSession['ends_at']);
                }

                $trainingSession['personal_plan_id'] = $personalPlanId;
                $trainingSessionSanitized = TrainingSessionMapper::sanitizeData($trainingSession, $operation);

                DB::table('training_sessions')->insert($trainingSessionSanitized);
            },
            $trainingSessions
        );

    }
    public function createPersonalPlan(array $data): int
    {
        $developmentPlan = DevelopmentPlanMapper::toDTO($data);

        $now = new DateTime();
        $developmentPlan['created_at'] = $now;
        $developmentPlan['updated_at'] = $now;

        $developmentPlan['starts_at'] = new DateTime($developmentPlan['starts_at']);

        if (!empty($data['ends_at'])) {
            $developmentPlan['ends_at'] = new DateTime($developmentPlan['ends_at']);
        }

        $developmentPlanSanitized = DevelopmentPlanMapper::sanitizeData($developmentPlan, AbstractMapper::OPERATION_CREATE);

        return DB::transaction(function () use ($developmentPlanSanitized, $data, $now) {
            $trainingSessions = $data['trainingSessions'];

            $developmentPlanId = DB::table('personal_plans')->insertGetId($developmentPlanSanitized);

            if (count($trainingSessions)) {
                $this->buildAndSaveTrainingSessionData($trainingSessions, AbstractMapper::OPERATION_CREATE, $developmentPlanId);
            }

            return $developmentPlanId;
        });
    }



    public function updateDevelopmentPlan(int $id, array $data)
    {
        $developmentPlan = DevelopmentPlanMapper::toDTO($data);

        $now = new DateTime();
        $developmentPlan['updated_at'] = $now;

        $developmentPlan['starts_at'] = new DateTime($developmentPlan['starts_at']);

        if (!empty($data['ends_at'])) {
            $developmentPlan['ends_at'] = new $developmentPlan($data['ends_at']);
        }

        $developmentPlanSanitized = DevelopmentPlanMapper::sanitizeData($developmentPlan, AbstractMapper::OPERATION_UPDATE);

        return DB::transaction(function () use ($data, $developmentPlanSanitized, $now, $id) {
            $this->removeTrainingSessionsFromDevelopmentPlan($id);

            $trainingSessions = $data['trainingSessions'];

            DB::table('personal_plans')
                ->where('id', '=' , $id)
                ->update($developmentPlanSanitized);

            if (count($trainingSessions)) {
                $this->buildAndSaveTrainingSessionData($trainingSessions, AbstractMapper::OPERATION_UPDATE, $id);
            }
        });
    }

    protected function removeTrainingSessionsFromDevelopmentPlan(int $id)
    {
        DB::table('training_sessions')->where('personal_plan_id', '=', $id)->delete();
    }

    public function getDevelopmentPlan(int $id): array
    {
        $result = DB::table('personal_plans as pp')
            ->select(
                'pp.id as pp_id',
                'pp.name as pp_name',
                'pp.is_active as pp_is_active',
                'pp.starts_at as pp_starts_at',
                'pp.ends_at as pp_ends_at',
                'pp.goals as pp_goals',
                'ts.id as ts_id',
                'ts.name as ts_name',
                'ts.is_active as ts_is_active',
                'ts.personal_plan_id as ts_personal_plan_id',
                'ts.starts_at as ts_starts_at',
                'ts.ends_at as ts_ends_at'
            )
            ->addSelect(
                'student.id as s_id',
                'student.first_name as s_first_name',
                'student.last_name as s_last_name',
                'student.email as s_email',
                'student.type as s_type',
                'student.user_role as s_user_role'
            )
            ->addSelect(
                'instructor.id as i_id',
                'instructor.first_name as i_first_name',
                'instructor.last_name as i_last_name',
                'instructor.email as i_email',
                'instructor.type as i_type',
                'instructor.user_role as i_user_role'
            )
            ->leftJoin('users as student', 'pp.student_id', '=', 'student.id')
            ->leftJoin('users as instructor', 'pp.instructor_id', '=', 'instructor.id')
            ->leftJoin('training_sessions as ts', 'ts.personal_plan_id', '=', 'pp.id')
            ->where('pp.id', '=', $id)
            ->get();

        $hydrated = [];

        foreach ($result->toArray() as $row) {
            if (!isset($hydrated[$row->pp_id])) {
                $item = [
                    'id' => $row->pp_id,
                    'name' => $row->pp_name,
                    'starts_at' => $row->pp_starts_at ? (new DateTime($row->pp_starts_at))->format('Y-m-d') : null,
                    'ends_at' => $row->pp_ends_at ? (new DateTime($row->pp_ends_at))->format('Y-m-d'): null,
                    'goals' => $row->pp_goals,
                    'is_active' => $row->pp_is_active,
                    'student' => [
                        'id' => $row->s_id,
                        'first_name' => $row->s_first_name,
                        'last_name' => $row->s_last_name,
                        'email' => $row->s_email,
                        'type' => $row->s_type,
                        'user_role' => $row->s_user_role,
                    ],
                    'instructor' => [
                        'id' => $row->i_id,
                        'first_name' => $row->i_first_name,
                        'last_name' => $row->i_last_name,
                        'email' => $row->i_email,
                        'type' => $row->i_type,
                        'user_role' => $row->i_user_role,
                    ],
                ];
                $hydrated[$row->pp_id] = $item;
            }

            if (!empty($hydrated[$row->ts_personal_plan_id])) {
                $hydrated[$row->ts_personal_plan_id]['training_sessions'][] = [
                    'id' => $row->ts_id,
                    'name' => $row->ts_name,
                    'is_active' => $row->ts_is_active,
                    'starts_at' => $row->ts_starts_at ? (new DateTime($row->pp_starts_at))->format('Y-m-d') : null,
                    'ends_at' => $row->ts_ends_at ? (new DateTime($row->pp_ends_at))->format('Y-m-d') : null,
                    'personal_plan_id' => $row->ts_personal_plan_id,
                ];
            }
        }

        return count($hydrated) === 1 ? DevelopmentPlanMapper::fromDTO((object)array_values($hydrated)[0]) : [];
    }

    public function getDevelopmentPlans(): array
    {
        $result = DB::table('personal_plans as pp')
            ->select(
                'pp.id as pp_id',
                'pp.name as pp_name',
                'pp.is_active as pp_is_active',
                'pp.starts_at as pp_starts_at',
                'pp.ends_at as pp_ends_at',
                'pp.goals as pp_goals',
                'ts.id as ts_id',
                'ts.name as ts_name',
                'ts.personal_plan_id as ts_personal_plan_id'
            )
            ->addSelect('student.id as s_id', 'student.first_name as s_first_name', 'student.last_name as s_last_name' )
            ->addSelect('instructor.id as i_id', 'instructor.first_name as i_first_name', 'instructor.last_name as i_last_name' )
            ->leftJoin('users as student', 'pp.student_id', '=', 'student.id')
            ->leftJoin('users as instructor', 'pp.instructor_id', '=', 'instructor.id')
            ->leftJoin('training_sessions as ts', 'ts.personal_plan_id', '=', 'pp.id')
            ->get();

        $hydrated = [];

        foreach ($result->toArray() as $row) {
            if (!isset($hydrated[$row->pp_id])) {
                $item = [
                    'id' => $row->pp_id,
                    'name' => $row->pp_name,
                    'starts_at' => $row->pp_starts_at,
                    'ends_at' => $row->pp_ends_at,
                    'goals' => $row->pp_goals,
                    'is_active' => $row->pp_is_active,
                    'student' => [
                        'id' => $row->s_id,
                        'first_name' => $row->s_first_name,
                        'last_name' => $row->s_last_name,
                    ],
                    'instructor' => [
                        'id' => $row->i_id,
                        'first_name' => $row->i_first_name,
                        'last_name' => $row->i_last_name,
                    ],
                ];
                $hydrated[$row->pp_id] = $item;
            }

            if (!empty($hydrated[$row->ts_personal_plan_id])) {
                $hydrated[$row->ts_personal_plan_id]['training_sessions'][] = [
                    'id' => $row->ts_id,
                    'name' => $row->ts_name
                ];
            }
        }

        return array_values(array_map(
            function ($developmentPlan) {
                return DevelopmentPlanMapper::fromDTO((object)$developmentPlan);
            },
            $hydrated
        ));
    }
}
