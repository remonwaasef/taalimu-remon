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
        // Trust only local proxies (adjust this in production to match your actual LB/Proxy IP)
        $middleware->trustProxies(at: ['127.0.0.1', '10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16']);

        // Add SetLocale middleware globally for web routes
        $middleware->web(prepend: [
            \App\Http\Middleware\IdentifyTenant::class,
        ], append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ContentSecurityPolicy::class,
            \App\Http\Middleware\BasicWAF::class,
            \App\Http\Middleware\PaginationLimit::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            'throttle:300,1',
        ]);

        $middleware->api(append: [
            'throttle:api',
        ]);

        // Payment gateway webhooks are server-to-server calls that cannot carry a CSRF token.
        // Their authenticity is verified inside the controllers (PayPal signature / Paymob HMAC).
        $middleware->validateCsrfTokens(except: [
            'webhooks/paypal',
            'webhooks/paymob',
        ]);

        // Configure redirect for unauthenticated users
        $middleware->redirectGuestsTo(function ($request) {
            // 1. Admin Routes -> Admin Login
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            $host = $request->getHost();
            $mainDomain = config('app.tenant_domain');

            // 2. Main Domain / Localhost -> Unified Login Portal
            // Check if host is exactly the main domain or www.maindomain
            if ($host === $mainDomain || $host === 'www.'.$mainDomain || $host === 'localhost') {
                return route('login.portal');
            }

            // 3. Tenant Subdomain -> Tenant Login
            // Only if it ends with the main domain and has a subdomain
            if ($mainDomain && str_ends_with($host, '.'.$mainDomain)) {
                $subdomain = substr($host, 0, -strlen('.'.$mainDomain));
                if ($subdomain && $subdomain !== 'www') {
                    return route('center.login', ['tenant' => $subdomain]);
                }
            }

            // Fallback
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
            'phone.verified' => \App\Http\Middleware\EnsurePhoneIsVerified::class,
            'onboarding.completed' => \App\Http\Middleware\EnsureOnboardingCompleted::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        \Sentry\Laravel\Integration::handles($exceptions);

        // Auto-capture all exceptions in tenant context
        $exceptions->report(function (\Throwable $e) {
            if (app()->bound('tenant') && app('tenant')) {
                try {
                    app(\App\Services\IssueLogger::class)->logException($e, request());
                } catch (\Throwable $logError) {
                    // Prevent infinite loops - just log to file
                    \Illuminate\Support\Facades\Log::error('Failed to log issue: '.$logError->getMessage());
                }
            }

            // Telegram Alert for critical errors only — skip expected exceptions (404s,
            // validation, auth) so the channel isn't flooded and internals aren't leaked.
            $isExpected = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                || $e instanceof \Illuminate\Validation\ValidationException
                || $e instanceof \Illuminate\Auth\AuthenticationException
                || $e instanceof \Illuminate\Auth\Access\AuthorizationException
                || $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException;

            if (! $isExpected) {
                try {
                    app(\App\Services\TelegramService::class)->sendExceptionAlert($e, request()->fullUrl(), auth()->user());
                } catch (\Throwable $telError) {
                    \Illuminate\Support\Facades\Log::error('Telegram notification failed: '.$telError->getMessage());
                }
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
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

                $isLocal = app()->environment('local');

                return response()->json([
                    'success' => false,
                    'message' => $isLocal ? $e->getMessage() : ($status === 500 ? 'An internal server error occurred.' : $e->getMessage()),
                    'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null,
                    'trace' => ($isLocal && $status === 500) ? $e->getTrace() : null,
                ], $status);
            }
        });
    })->create();
