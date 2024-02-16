<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
            //
        });
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {

        if ($e instanceof ModelNotFoundException) {
            return response()->json(['status' => 'failed', 'message' => 'Model not found'], 404);
        }

        if ($e instanceof ValidationException) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 400);
        }

        if ($e instanceof QueryException) {
            return response()->json(['status' => 'failed', 'message' => 'db exception'], 400);
        }

        if ($e instanceof Exception) {
            return response()->json(['status' => 'failed', 'message' => 'internal server error'], 500);
        }

        return $this->prepareJsonResponse($request, $e);
    }
}
