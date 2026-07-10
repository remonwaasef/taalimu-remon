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
            if ($user->hasRole(['instructor', 'center_admin'])) {
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
        if ($this->instructor && $course->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الكورس');
        }
    }

    protected function authorizeSchedule($schedule)
    {
        if ($this->instructor && $schedule->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الموعد');
        }
    }

    protected function authorizeInstructor($student)
    {
        $instructor = $this->instructor;
        $courseIds = Course::where('instructor_id', $instructor->id)->pluck('id');
        $isRelated = $student->enrollments()->whereIn('course_id', $courseIds)->exists();

        if (! $isRelated) {
            abort(403, __('instructor::messages.unauthorized'));
        }
    }
}
