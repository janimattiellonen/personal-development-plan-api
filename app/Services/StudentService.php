<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Query\Builder;

class StudentService
{
    public function createUser(array $data): int
    {
        $now = new \DateTime();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $data['password'] = Hash::make($data['password']);

        $age = $data['age'];
        unset($data['age']);

        $createdUserId = DB::table('users')->insertGetId($data);

       return DB::table('profiles')->insertGetId([
            'age' => $age,
            'user_id' => $createdUserId,
            'created_at' => $now,
            'updated_at' => $now
        ]);
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
