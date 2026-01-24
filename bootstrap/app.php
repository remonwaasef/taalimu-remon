<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            if (app()->environment('local') && file_exists(__DIR__.'/../routes/debug.php')) {
                Route::middleware('web')->group(__DIR__.'/../routes/debug.php');
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add SetLocale middleware globally for web routes
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ContentSecurityPolicy::class,
            \App\Http\Middleware\BasicWAF::class,
            \App\Http\Middleware\PaginationLimit::class,
            'throttle:60,1',
        ]);

        $middleware->api(append: [
            'throttle:api',
        ]);
        
        // Temporarily disable CSRF for debugging
        // $middleware->validateCsrfTokens(except: [
        //     '*/login',
        // ]);
        
        // Configure redirect for unauthenticated users
        $middleware->redirectGuestsTo(function ($request) {
            // Logging removed for production
            // Check if request is for a tenant subdomain
            $host = $request->getHost();
            $parts = explode('.', $host);
            
            // If subdomain exists (not just 'localhost')
            if (count($parts) > 1 && $parts[0] !== 'www') {
                return route('center.login', ['tenant' => $parts[0]]);
            }
            
            // Otherwise redirect to main login portal
            return route('login.portal');
        });

        // Middleware Aliases
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'enrolled' => \App\Http\Middleware\CheckEnrollment::class,
            '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'force_password_change' => \App\Http\Middleware\ForcePasswordChange::class,
            'feature' => \App\Http\Middleware\CheckFeature::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.'
                ], 404);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                $status = 500;
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $status = 401;
                } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                    $status = 422;
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                }

                $isLocal = app()->environment('local') || config('app.debug');
                return response()->json([
                    'success' => false,
                    'message' => $isLocal ? $e->getMessage() : ($status === 500 ? 'An internal server error occurred.' : $e->getMessage()),
                    'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null,
                    'trace' => ($isLocal && $status === 500) ? $e->getTrace() : null,
                ], $status);
            }
        });
    })->create();
