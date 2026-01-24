<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
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

        // Register Tenant Model Observers for Caching
        \App\Models\User::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\Instructor::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\Course::observe(\App\Observers\TenantModelObserver::class);
        \App\Models\Classroom::observe(\App\Observers\TenantModelObserver::class);
        \Modules\Center\Models\Branch::observe(\App\Observers\TenantModelObserver::class);
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            $limit = app()->environment('local') ? 100 : 5;
            return \Illuminate\Cache\RateLimiting\Limit::perMinute($limit)->by($request->email.$request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('registration', function (\Illuminate\Http\Request $request) {
            $limit = app()->environment('local') ? 50 : 5;
            return \Illuminate\Cache\RateLimiting\Limit::perMinute($limit)->by($request->ip());
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

        \Illuminate\Support\Facades\RateLimiter::for('global', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(1000)->by($request->ip());
        });
    }
}
