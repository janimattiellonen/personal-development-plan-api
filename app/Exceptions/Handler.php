<?php

namespace App\Exceptions;

use App\Services\Exception\RecordNotFoundException;
use App\Services\Exception\BaseException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
           Log::error($e->getMessage());
        });

        $this->renderable(function (RecordNotFoundException $e, Request $request) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        });

        $this->renderable(function (BaseException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], $e->status);
        });

        $this->renderable(function (\Exception $e, Request $request) {
            return response()->json([
                'message' => '(Temporary message): Server error: ' . $e->getMessage(),
            ], 500);
        });

    }
}
