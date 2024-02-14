<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;

class StudentController
{
    public function create(StudentRequest $request, StudentService $studentService)
    {
        $data = $request->post();

        $userId = $studentService->createUser($data);

        return response()->json(['status' => true, 'id' => $userId], 201);
    }

    public function getAllStudents(StudentService $studentService)
    {
        return $studentService->getAllStudents();
    }

    public function findStudents(Request $request, StudentService $studentService)
    {
        $term = $request->query->get('term', null);
        $type = $request->query->get('type', null);

        $acceptedTypes = ['student', 'instructor'];

        if (!empty($type) && !in_array($type, $acceptedTypes)) {
            return response()
                ->json(['status' => 'error', 'message' => sprintf('Invalid type %s provided', $type)], 400);
        }

        $student = !!$term ?  $studentService->searchStudents($term, $type) : [];

        return response()->json(['status' => true, 'data' => $student]);

    }
}
