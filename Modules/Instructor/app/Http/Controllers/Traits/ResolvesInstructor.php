<?php

namespace Modules\Instructor\Http\Controllers\Traits;

use Illuminate\Support\Facades\Log;

trait ResolvesInstructor
{
    protected $instructor;

    protected $tenant;

    public function callAction($method, $parameters)
    {
        if (app()->bound('tenant')) {
            $this->tenant = app('tenant');
            view()->share('tenant', $this->tenant);
        }

        if (auth()->check()) {
            $this->instructor = $this->resolveInstructor();
            view()->share('instructor', $this->instructor);
        }

        return $this->{$method}(...array_values($parameters));
    }

    protected function resolveInstructor()
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }

        $instructor = $user->instructor;

        if (! $instructor) {
            $allowedRoles = ['instructor', 'center_admin', 'center_owner', 'admin'];
            $hasAllowedRole = in_array(strtolower((string) $user->role), $allowedRoles, true) || $user->hasAnyRole($allowedRoles);

            if ($hasAllowedRole) {
                $instructor = \App\Models\Instructor::create([
                    'tenant_id' => $user->tenant_id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => 'active',
                ]);
                Log::info("Auto-created instructor profile for user: {$user->id}");
            }
        }

        return $instructor;
    }

    protected function authorizeCourse($course)
    {
        // SEC-02: fail closed — a missing instructor profile must never pass.
        if (! $this->instructor || $course->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الكورس');
        }
    }

    protected function authorizeSchedule($schedule)
    {
        // SEC-02: fail closed — a missing instructor profile must never pass.
        if (! $this->instructor || $schedule->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الموعد');
        }
    }

    protected function authorizeInstructor($student)
    {
        $instructor = $this->instructor;
        if (! $instructor) {
            abort(403, __('instructor::messages.unauthorized'));
        }

        $isRelated = $student->enrollments()->whereIn('course_id', $instructor->courses->pluck('id'))->exists();

        if (! $isRelated) {
            abort(403, __('instructor::messages.unauthorized'));
        }
    }
}
