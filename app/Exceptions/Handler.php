<?php

namespace App\Exceptions;

use Exception;
use App\Library\Common;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an json response.
     *
     * @SuppressWarnings("unused")
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.errors.not_found', ['entry' => $exception->getModel()]),
            ], config('app.response_code.not_found'));
        } elseif ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => $exception->validator->errors()->first(),
            ], config('app.response_code.unprocessable_entity'));
        } elseif ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], config('app.response_code.forbidden'));
        } elseif ($exception instanceof HttpException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        return response()->json([
            'success' => false,
            'message' => config('app.mode') == Common::MODE_STG ?
                            $exception->getMessage() : trans('messages.errors.server_error'),
        ], 503);
    }
}
