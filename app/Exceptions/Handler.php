<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // return parent::render($request, $exception);
        $rendered = parent::render($request, $exception);
        DB::rollBack();
        if($exception instanceof NotFoundHttpException)
            return response()->json([
                'error' => [
                    'code' => $rendered->getStatusCode(),
                    'success' => false,
                    'message' => !empty($exception->getMessage()) ? $exception->getMessage() : "Route not found"
                ]
            ], $rendered->getStatusCode());
        if($exception instanceof MethodNotAllowedHttpException)
            return response()->json([
                'error' => [
                    'code' => $rendered->getStatusCode(),
                    'success' => false,
                    'message' => !empty($exception->getMessage()) ? $exception->getMessage() : "This method is not allowed for this route"
                ]
            ], $rendered->getStatusCode());
        return response()->json([
            'error' => [
                'code' => $rendered->getStatusCode(),
                'success' => false,
                'message' => $exception->getMessage()
            ]
        ], $rendered->getStatusCode());
    }
}
