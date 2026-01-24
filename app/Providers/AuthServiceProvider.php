<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\Instructor;
use Spatie\Permission\Models\Role;
use Modules\Center\Models\Branch;
use App\Models\Schedule;
use App\Policies\AssignmentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\QuizPolicy;
use App\Policies\StudentPolicy;
use App\Policies\InstructorPolicy;
use App\Policies\RolePolicy;
use App\Policies\BranchPolicy;
use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        // New Policies for Authorization Security
        \App\Models\Tenant::class => \App\Policies\TenantPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\QuizAttempt::class => \App\Policies\QuizAttemptPolicy::class,
        \App\Models\AssignmentSubmission::class => \App\Policies\AssignmentSubmissionPolicy::class,
        \Modules\Center\Models\Attendance::class => \App\Policies\AttendancePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
