<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\Student;
use App\Policies\AssignmentPolicy;
use App\Policies\BranchPolicy;
use App\Policies\CoursePolicy;
use App\Policies\InstructorPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\QuizPolicy;
use App\Policies\RolePolicy;
use App\Policies\SchedulePolicy;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Center\Models\Branch;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Quiz::class => QuizPolicy::class,
        Assignment::class => AssignmentPolicy::class,
        Course::class => CoursePolicy::class,
        Student::class => StudentPolicy::class,
        Instructor::class => InstructorPolicy::class,
        Role::class => RolePolicy::class,
        Branch::class => BranchPolicy::class,
        Schedule::class => SchedulePolicy::class,
        Question::class => QuestionPolicy::class,
        // New Policies for Authorization Security
        \App\Models\Tenant::class => \App\Policies\TenantPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\QuizAttempt::class => \App\Policies\QuizAttemptPolicy::class,
        \App\Models\AssignmentSubmission::class => \App\Policies\AssignmentSubmissionPolicy::class,
        \Modules\Center\Models\Attendance::class => \App\Policies\AttendancePolicy::class,
        // Explicit registration (model moved from an anomalous namespace where
        // auto-discovery could never find this policy).
        \App\Models\Asset::class => \App\Policies\AssetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Grant "Super Admin" all permissions
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // Debugging Authorization
            /*
            \Illuminate\Support\Facades\Log::info('Gate::before Check', [
                'user_id' => $user->id,
                'ability' => $ability,
                'role_attribute' => $user->role,
                'spatie_roles' => $user->getRoleNames(),
                'is_super_admin' => $user->hasRole('super_admin') || $user->role === 'super_admin'
            ]);
            */

            // Case-insensitive check for Super Admin, Admin and Center Admin
            // These roles get full access to all features without needing individual permissions.
            // SEC-01: the literal 'admin'/'Admin' roles grant a bypass ONLY for global
            // accounts (tenant_id null); a tenant user holding a custom role named
            // 'admin' must never inherit full-panel powers.
            $roleColumn = strtolower($user->role ?? '');

            // SEC-08: tenants must only be administered from within their own
            // tenant context; tenant-scoped super_admins/center admins get no
            // bypass while a different tenant's context is active.
            $tenantContext = app()->bound('tenant') ? app('tenant') : null;
            $inOwnTenantContext = $tenantContext === null
                || (string) $tenantContext->id === (string) $user->tenant_id;

            if ($roleColumn === 'super_admin') {
                return $inOwnTenantContext ? true : null;
            }

            if ($user->tenant_id === null && $roleColumn === 'admin') {
                return true;
            }

            // SEC-08: center_admin/center_owner bypass only within their own
            // tenant context (or as a global account). Without this check a
            // center admin from tenant A would still pass every Gate::before
            // while operating inside tenant B's session/domain.
            if (in_array($roleColumn, ['center_admin', 'center_owner']) ||
                $user->hasAnyRole(['center_admin', 'Center Owner', 'center_owner'])) {
                return $inOwnTenantContext ? true : null;
            }

            if ($user->tenant_id === null && (
                $user->hasAnyRole(['super_admin', 'Super Admin', 'admin', 'Admin'])
            )) {
                return true;
            }
        });

        // Zero DB Hits: Cache authenticated user data in Redis with tenant isolation
        \Illuminate\Support\Facades\Auth::provider('cached', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends \Illuminate\Auth\EloquentUserProvider
            {
                public function retrieveById($identifier)
                {
                    $tenantPrefix = app()->bound('tenant') && app('tenant')
                        ? "tenant_{$app['tenant']->id}:"
                        : 'global:';

                    return \Illuminate\Support\Facades\Cache::remember("{$tenantPrefix}user_cache_{$identifier}", 3600, function () use ($identifier) {
                        return parent::retrieveById($identifier);
                    });
                }
            };
        });

        // High-Scale: Use Cached Personal Access Tokens for API
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
    }
}
