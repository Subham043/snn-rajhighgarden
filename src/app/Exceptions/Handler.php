<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Contracts\Encryption\DecryptException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\UnauthorizedAdminAccessException;
use App\Exceptions\UserAccessException;
use App\Exceptions\CustomJsonException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException && $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Too many attempts, please try again after sometime',
            ], 429);
        }

        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 405);
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Oops! No data found ',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Oops! Invalid link',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof DecryptException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Oops! You have entered invalid link',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof UnauthorizedAdminAccessException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => "Oops! You don't have the permission to access this!",
            ], 403);
        }

        if ($exception instanceof UserAccessException && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->showCustomErrorMessage(),
                'error_code' => $exception->showCustomErrorCode(),
                'error_id' => $exception->showUserId(),
            ], 403);
        }

        if ($exception instanceof CustomJsonException && $request->wantsJson()) {
            return response()->json([
                'status' => $exception->showCustomErrorStatus(),
                'message' => $exception->showCustomErrorMessage(),
            ], $exception->showCustomErrorCode());
        }

        return parent::render($request, $exception);
    }
}
