<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // In production, don't expose detailed error information
        if (config('app.debug') === false) {
            return $this->renderProductionException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render a production-safe exception response.
     */
    protected function renderProductionException($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->route('login.portal');
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return response()->view('errors.403', [], 403);
        }

        // Generic error page for all other exceptions
        return response()->view('errors.500', [], 500);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('login.portal');
    }
}
