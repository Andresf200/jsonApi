<?php

namespace App\Exceptions;

use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(fn (NotFoundHttpException $e) => throw new JsonApi\NotFoundHttpException);
        $this->renderable(fn (BadRequestHttpException $e) => throw new JsonApi\BadRequestHttpException($e->getMessage()));
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        if (!$request->routeIs('api.v1.login')){
            return new JsonApiValidationErrorResponse($exception, 422);
        }
        return parent::invalidJson($request, $exception);
    }
}
