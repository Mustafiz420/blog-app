<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return $this->handleApiException($request, $e);
            }
        });
    }

    /**
     * Convert a given exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException($request, Throwable $e): JsonResponse
    {
        $status = 500;
        $type = 'Exception';
        $message = 'An error occurred. Please try again later.';

        if ($e instanceof ValidationException) {
            $type = 'ValidationException';
            $message = $e->validator->errors()->getMessages();
            $status = 422; // Unprocessable Entity
        } elseif ($e instanceof NotFoundHttpException) {
            $type = 'NotFoundHttpException';
            $status = 404;
            $message = 'The requested resource was not found.';
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: 'An API error occurred.';
        }

        if (config('app.debug')) {
            return response()->json([
                'error' => [
                    'type' => $type,
                    'message' => $message,
                    'trace' => explode("\n", $e->getTraceAsString())
                ]
            ], $status);
        }

        return response()->json([
            'error' => [
                'type' => $type,
                'message' => $message
            ]
        ], $status);
    }
}
