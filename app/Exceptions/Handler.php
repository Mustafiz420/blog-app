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
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

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

    protected function handleApiException($request, Throwable $e): JsonResponse
    {
        $status = 500;
        $type = 'Exception';
        $message = 'An error occurred. Please try again later.';
        $errors = [];

        if ($e instanceof ValidationException) {
            $type = 'ValidationException';
            $message = 'Validation failed.';
            $errors = $e->validator->errors()->getMessages();
            $status = 422;
        } elseif ($e instanceof NotFoundHttpException) {
            $type = 'NotFoundHttpException';
            $message = 'The requested resource was not found.';
            $status = 404;
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: 'An API error occurred.';
        }

        $response = [
            'error' => [
                'type' => $type,
                'message' => $message,
            ],
        ];

        if (!empty($errors)) {
            $response['error']['details'] = $errors;
        }

        if (config('app.debug')) {
            $response['error']['trace'] = explode("\n", $e->getTraceAsString());
        }

        return response()->json($response, $status);
    }
}
