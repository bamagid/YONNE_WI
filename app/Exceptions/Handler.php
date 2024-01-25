<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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


    // public function render($request, Throwable $exception)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'error' => $exception->getMessage(),
    //         ], $exception->getStatusCode() ?: 400);
    //     }

    //     return parent::render($request, $exception);
    // }
    // /**
    //  * Register the exception handling callbacks for the application.
    //  */
    // public function register(): void
    // {
    //     $this->renderable(function (Throwable $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ], $e->getStatusCode() ?: 400);
    //     });
    // }
}