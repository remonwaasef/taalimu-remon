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
            // These roles get full access to all features without needing individual permissions
            $bypassRoles = ['super_admin', 'Super Admin', 'admin', 'Admin', 'center_admin'];
            if ($user->hasAnyRole($bypassRoles) ||
                in_array(strtolower($user->role ?? ''), ['super_admin', 'admin', 'center_admin'])) {
                return true;
            }
        });

        // Zero DB Hits: Cache authenticated user data in Redis
        \Illuminate\Support\Facades\Auth::provider('cached', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends \Illuminate\Auth\EloquentUserProvider
            {
                public function retrieveById($identifier)
                {
                    return \Illuminate\Support\Facades\Cache::remember("user_cache_{$identifier}", 3600, function () use ($identifier) {
                        return parent::retrieveById($identifier);
                    });
                }
            };
        });

        // High-Scale: Use Cached Personal Access Tokens for API
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
    }
}
