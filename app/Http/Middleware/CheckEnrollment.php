<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $course = $request->route('course'); // Assuming route model binding or parameter

        if (! $user || ! $course) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Check if user is enrolled
        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if (! $enrollment) {
            // Redirect to course details/enrollment page
            return redirect()->route('center.courses.show', ['course' => $course->id])
                ->with('error', 'You must be enrolled to access this content.');
        }

        return $next($request);
    }
}
