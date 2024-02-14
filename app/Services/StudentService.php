<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Query\Builder;

class StudentService
{
    public function createUser(array $data): int
    {
        $age = $data['age'];

        $student = StudentMapper::toDTO($data);

        $now = new \DateTime();
        $student['created_at'] = $now;
        $student['updated_at'] = $now;
        $student['password'] = Hash::make($data['password']);

        $studentSanitized = StudentMapper::sanitizeData($student, AbstractMapper::OPERATION_CREATE);

        $createdUserId = DB::table('users')->insertGetId($studentSanitized);

        return DB::table('profiles')->insertGetId([
            'age' => $age,
            'user_id' => $createdUserId,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    public function getAllStudents()
    {
        return DB::table('users')->get(['id', 'first_name', 'last_name', 'email']);
    }

    public function searchStudents(string $searchTerm, string $type)
    {
        return DB::table('users')
            ->where(function (Builder $query) use ($searchTerm) {
                $query->where('first_name', 'like', "{$searchTerm}%")
                ->orWhere('last_name', 'like', "{$searchTerm}%");
            })
            ->whereRaw('type = ?', [$type])

            ->get(['id', 'first_name', 'last_name', 'email']);
    }
}
