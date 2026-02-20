<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ForceOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Only apply to center admins / instructors or roles that manage the center
        // Students are exempt from this forcing
        if (!$user || !$user->tenant_id || $user->role === 'student') {
            return $next($request);
        }

        // List of routes that are allowed even during onboarding
        $allowedRoutes = [
            'center.dashboard',
            'center.logout',
            'center.onboarding.*', 
            'lang.switch',
            'admin.*', // System admins can still access admin panel
        ];

        $currentRoute = $request->route() ? $request->route()->getName() : null;

        if (!$currentRoute) {
            return $next($request);
        }

        // Check if current route is allowed
        foreach ($allowedRoutes as $allowed) {
            if (Str::is($allowed, $currentRoute)) {
                return $next($request);
            }
        }

        // Check if onboarding is completed
        $tenant = app('tenant');
        if (!$tenant) {
            return $next($request);
        }

        $isCompleted = $tenant->settings['onboarding_completed'] ?? false;

        if (!$isCompleted) {
            // Check if it's REALLY incomplete (double check with actual data)
            $isReallyCompleted = $this->checkOnboardingStatus($tenant);
            
            if (!$isReallyCompleted) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Please complete onboarding first',
                        'onboarding_required' => true
                    ], 403);
                }
                return redirect()->route('center.dashboard')->with('onboarding_info', 'يرجى إكمال خطوات الإعداد أولاً للوصول لباقي المميزات');
            } else {
                // Auto-mark as completed if data is found
                $settings = $tenant->settings ?? [];
                $settings['onboarding_completed'] = true;
                $tenant->settings = $settings;
                $tenant->save();
            }
        }

        return $next($request);
    }

    private function checkOnboardingStatus($tenant)
    {
        $tenantId = $tenant->id;
        
        // Essential steps:
        $hasStages = \App\Models\Stage::where('tenant_id', $tenantId)->exists();
        $hasInstructor = \App\Models\Instructor::where('tenant_id', $tenantId)->exists();
        $hasCourse = \App\Models\Course::where('tenant_id', $tenantId)->exists();
        
        // We consider it done if they have Stage, Instructor and Course. 
        // Student and Enrollment can be part of the flow but maybe not blocking if they just want to explore?
        // Actually, the user specifically mentioned Student and Course registration.
        
        return $hasStages && $hasInstructor && $hasCourse;
    }
}
