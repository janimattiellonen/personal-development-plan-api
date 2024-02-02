<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use \DateTime;

class DevelopmentPlanService
{
    public function createPersonalPlan(array $data): int
    {
        $now = new DateTime();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        $data['starts_at'] = new DateTime($data['starts_at']);

        if (!empty($data['ends_at'])) {
            $data['ends_at'] = new DateTime($data['ends_at']);
        }

        return DB::transaction(function () use ($data, $now) {
            $trainingSessions = $data['training_sessions'];

            unset($data['training_sessions']);

            $developmentPlanId = DB::table('personal_plans')->insertGetId($data);

            if (count($trainingSessions)) {
                array_map(
                    function ($trainingSession) use ($developmentPlanId, $now) {
                        $trainingSession['created_at'] = $now;
                        $trainingSession['updated_at'] = $now;

                        $trainingSession['starts_at'] = new DateTime($trainingSession['starts_at']);

                        if (!empty($trainingSession['ends_at'])) {
                            $trainingSession['ends_at'] = new DateTime($trainingSession['ends_at']);
                        }

                        $trainingSession['personal_plan_id'] = $developmentPlanId;
                        DB::table('training_sessions')->insert($trainingSession);
                    },
                    $trainingSessions
                );
            }

            return $developmentPlanId;
        });
    }

    public function updateDevelopmentPlan(int $id, array $data)
    {
        $now = new DateTime();
        $data['updated_at'] = $now;

        $data['starts_at'] = new DateTime($data['starts_at']);

        if (!empty($data['ends_at'])) {
            $data['ends_at'] = new DateTime($data['ends_at']);
        }

        return DB::transaction(function () use ($data, $now, $id) {
            $this->removeTrainingSessionsFromDevelopmentPlan($id);

            $trainingSessions = $data['training_sessions'];

            unset($data['training_sessions']);

            DB::table('personal_plans')
                ->where('id', '=' , $id)
                ->update($data);

            if (count($trainingSessions)) {
                array_map(
                    function ($trainingSession) use ($id, $now) {
                        $trainingSession['created_at'] = $now;
                        $trainingSession['updated_at'] = $now;

                        $trainingSession['starts_at'] = new DateTime($trainingSession['starts_at']);

                        if (!empty($trainingSession['ends_at'])) {
                            $trainingSession['ends_at'] = new DateTime($trainingSession['ends_at']);
                        }

                        $trainingSession['personal_plan_id'] = $id;
                        DB::table('training_sessions')->insert($trainingSession);
                    },
                    $trainingSessions
                );
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
            ->addSelect('student.id as s_id', 'student.first_name as s_first_name', 'student.last_name as s_last_name', 'student.email as s_email' )
            ->addSelect('instructor.id as i_id', 'instructor.first_name as i_first_name', 'instructor.last_name as i_last_name', 'instructor.email as i_email' )
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
                    ],
                    'instructor' => [
                        'id' => $row->i_id,
                        'first_name' => $row->i_first_name,
                        'last_name' => $row->i_last_name,
                        'email' => $row->i_email,

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
                ];
            }


        }

        return count($hydrated) === 1 ? array_values($hydrated)[0] : [];
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

        return array_values($hydrated);
    }
}
