<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DevelopmentPlanController;
use App\Http\Controllers\ExerciseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::get('test', [TestController::class, 'test']);

    Route::prefix('admin')->group(function() {
        Route::post('clubs', [ClubController::class, 'create']);
        Route::get('clubs', [ClubController::class, 'getClubs']);
        Route::get('clubs/{id}', [ClubController::class, 'getClub']);
        Route::put('clubs/{id}', [ClubController::class, 'update']);
        Route::delete('clubs/{id}', [ClubController::class, 'remove']);

        Route::get('students', [StudentController::class, 'findStudents']);
        Route::post('students', [StudentController::class, 'create']);

        Route::post('development-plans', [DevelopmentPlanController::class, 'create']);
        Route::put('development-plans/{id}', [DevelopmentPlanController::class, 'update']);
        Route::get('development-plans/{id}', [DevelopmentPlanController::class, 'getDevelopmentPlan']);
        Route::get('development-plans', [DevelopmentPlanController::class, 'getDevelopmentPlans']);

        Route::post('exercises', [ExerciseController::class, 'create']);
        Route::put('exercises/{id}', [ExerciseController::class, 'update']);
        Route::get('exercises', [ExerciseController::class, 'getExercises']);
        Route::get('exercises/{id}', [ExerciseController::class, 'getExercise']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
