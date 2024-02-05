<?php

namespace App\Exceptions;

use App\Services\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

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

        $this->renderable(function (NotFoundException $e, Request $request) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        });

        $this->renderable(function (\Exception $e, Request $request) {
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        });
    }
}
