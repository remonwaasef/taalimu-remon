<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Whether the Redis fallback warning has been logged for the current process.
     */
    protected static bool $redisFallbackLogged = false;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureResilientCaching();
        if (app()->environment('production') || \Illuminate\Support\Str::startsWith(config('app.url'), 'https://') || request()->header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Gate::policy(\App\Models\AssignmentSubmission::class, \App\Policies\AssignmentSubmissionPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Assignment::class, \App\Policies\AssignmentPolicy::class);
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        \Laravel\Cashier\Cashier::useCustomerModel(\App\Models\Tenant::class);
        \Laravel\Cashier\Cashier::useSubscriptionModel(\App\Models\Subscription::class);

        // Define Rate Limiters
        $this->configureRateLimiting();

        // Register Authentication Auditor
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\AuthenticationSubscriber::class);

        // Prevent N+1 queries in development
        Model::preventLazyLoading(! app()->isProduction());

        // Enforce strict password policies across the entire platform
        \Illuminate\Validation\Rules\Password::defaults(function () {
            $rule = \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols();

            return app()->isProduction() ? $rule->uncompromised() : $rule;
        });

        // Global Session/Cookie compatibility for multi-subdomain
        // Set this in boot() to ensure it's ready BEFORE StartSession middleware runs
        $mainDomain = config('app.tenant_domain');
        $host = request()->getHost();

        if ($mainDomain && $mainDomain !== 'localhost' && ! str_contains($mainDomain, 'localhost')) {
            // Only force the session domain if the request is actually accessing via the main domain/subdomain
            // This prevents breaking sessions (419 errors) on Mobile when testing via LAN IPs like 192.168.x.x
            if (str_ends_with($host, str_replace('www.', '', $mainDomain))) {
                config(['session.domain' => '.'.str_replace('www.', '', $mainDomain)]);
            }

            // PWAs and Cross-Domain Token Logins on mobile often require SameSite=None and Secure
            if (request()->secure() || app()->environment('production')) {
                config(['session.same_site' => 'none']);
                config(['session.secure' => true]);
            } else {
                config(['session.same_site' => 'lax']);
            }
        }

        // Register Tenant Model Observers for Caching
        \App\Models\User::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Instructor::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\Course::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\Classroom::observe(\App\Observers\TenantModelObserver::class);
        \Modules\Center\Models\Branch::observe(\App\Observers\TenantModelObserver::class);

        // Blade directive for Feature Flags
        \Illuminate\Support\Facades\Blade::if('feature', function ($feature) {
            $tenant = app(\App\Services\TenantService::class)->getTenant();
            if (! $tenant) {
                return false;
            }

            return $tenant->hasFeature($feature);
        });
    }

    /**
     * Verify Redis availability and gracefully fall back to the database
     * drivers when it is unreachable, keeping the platform online.
     *
     * Only runs when the active environment is configured to use Redis.
     */
    protected function configureResilientCaching(): void
    {
        $usesRedis = config('cache.default') === 'redis'
            || config('session.driver') === 'redis'
            || config('queue.default') === 'redis';

        if (! $usesRedis) {
            return;
        }

        try {
            \Illuminate\Support\Facades\Redis::connection('default')->ping();
        } catch (\Throwable $e) {
            config([
                'cache.default' => 'database',
                'session.driver' => 'database',
                'queue.default' => 'database',
            ]);

            if (! static::$redisFallbackLogged) {
                static::$redisFallbackLogged = true;

                \Illuminate\Support\Facades\Log::warning(
                    'Redis is unreachable; falling back to database drivers for cache, session and queue.',
                    ['error' => $e->getMessage()]
                );
            }
        }
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // NOTE: limits are relaxed based on the app environment ONLY.
        // Request IPs must never be trusted for this (spoofable via X-Forwarded-For).
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            $limit = is_relaxed_throttle_env() ? 100 : 5;

            return \Illuminate\Cache\RateLimiting\Limit::perMinute($limit)->by($request->email.$request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('password-reset', function (\Illuminate\Http\Request $request) {
            $limit = is_relaxed_throttle_env() ? 100 : 3;

            return \Illuminate\Cache\RateLimiting\Limit::perMinute($limit)->by($request->email.$request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('registration', function (\Illuminate\Http\Request $request) {
            $limit = is_relaxed_throttle_env() ? 100 : 5;

            return \Illuminate\Cache\RateLimiting\Limit::perMinute($limit)->by($request->ip());
        });

        // Protect QR Scanner Endpoints against spam scanning
        \Illuminate\Support\Facades\RateLimiter::for('scanner', function (\Illuminate\Http\Request $request) {
            if ($request->user()) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()->id);
            }

            return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('coupons', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
            if ($request->user()) {
                // Higher limit for admins/instructors
                if ($request->user()->hasAnyRole(['admin', 'center_admin', 'instructor'])) {
                    return \Illuminate\Cache\RateLimiting\Limit::perMinute(300)->by($request->user()->id);
                }

                // Standard limit for students
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(120)->by($request->user()->id);
            }

            // Strict limit for guests
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(30)->by($request->ip());
        });

        // AI assistant calls hit an external paid API (Gemini): keep them strict
        \Illuminate\Support\Facades\RateLimiter::for('ai', function (\Illuminate\Http\Request $request) {
            if ($request->user()) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->user()->id);
            }

            return \Illuminate\Cache\RateLimiting\Limit::perMinute(2)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('global', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(1000)->by($request->ip());
        });
    }
}
