<?php

namespace Modules\Center\Http\Controllers;

use App\Models\Course;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;

class PublicCourseController extends Controller
{
    /**
     * Display public courses for a tenant.
     */
    public function index(Request $request, string $tenantDomain)
    {
        $tenant = Tenant::where('domain', $tenantDomain)
            ->where('status', 'active')
            ->firstOrFail();

        app()->instance('tenant', $tenant);

        $courses = Course::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with('instructor')
            ->orderByDesc('created_at')
            ->paginate(12);

        $seoData = [
            'title' => 'الدورات المتاحة | '.$tenant->name,
            'description' => 'تصفح الدورات المتاحة في '.$tenant->name.' - دورات تعليمية متنوعة',
            'keywords' => 'دورات, تعليم, '.$tenant->name,
            'og_image' => $tenant->settings['appearance']['logo'] ?? null,
        ];

        return view('center::public.courses-index', compact('tenant', 'courses', 'seoData'));
    }

    /**
     * Display a single course publicly.
     */
    public function show(string $tenantDomain, Course $course)
    {
        $tenant = Tenant::where('domain', $tenantDomain)
            ->where('status', 'active')
            ->firstOrFail();

        app()->instance('tenant', $tenant);

        $course = Course::where('tenant_id', $tenant->id)
            ->where('id', $course->id)
            ->where('status', 'active')
            ->with(['instructor', 'schedules'])
            ->firstOrFail();

        $seoData = [
            'title' => $course->title.' | '.$tenant->name,
            'description' => $course->description ?? 'معلومات عن '.$course->title.' في '.$tenant->name,
            'keywords' => $course->title.', '.$tenant->name.', دورات',
            'og_image' => $course->image ? asset('storage/'.$course->image) : null,
        ];

        return view('center::public.courses-show', compact('tenant', 'course', 'seoData'));
    }
}
