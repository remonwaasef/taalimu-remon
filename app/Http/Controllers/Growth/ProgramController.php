<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Services\GrowthEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function __construct(
        protected GrowthEventService $eventService
    ) {}

    public function index(string $slug)
    {
        $row = DB::table('public_profiles')
            ->where('slug', $slug)
            ->where('published', true)
            ->first();

        if (! $row) {
            abort(404);
        }

        $tenant = Tenant::find($row->tenant_id);

        if (! $tenant) {
            abort(404);
        }

        $cacheKey = "growth:tenant_{$tenant->id}:published_courses";

        $courses = Cache::remember($cacheKey, 300, function () use ($tenant) {
            return Course::where('tenant_id', $tenant->id)
                ->where('published', true)
                ->with(['instructor'])
                ->orderByDesc('start_date')
                ->get();
        });

        $profile = PublicProfile::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->find($row->id);

        $seoData = [
            'title' => __('Programs') . ' — ' . $tenant->name,
            'description' => __('Available programs and courses at') . ' ' . $tenant->name,
            'canonical' => route('growth.programs.index', $slug),
        ];

        return view('growth.public.programs', compact('profile', 'tenant', 'courses', 'seoData'));
    }

    public function show(string $profileSlug, string $courseSlug)
    {
        $row = DB::table('public_profiles')
            ->where('slug', $profileSlug)
            ->where('published', true)
            ->first();

        if (! $row) {
            abort(404);
        }

        $tenant = Tenant::find($row->tenant_id);

        if (! $tenant) {
            abort(404);
        }

        $course = Course::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('tenant_id', $tenant->id)
            ->where('slug', $courseSlug)
            ->where('published', true)
            ->with(['instructor', 'schedules', 'schedules.classroom'])
            ->firstOrFail();

        $waitlistCount = DB::table('waitlists')
            ->where('tenant_id', $tenant->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->count();

        $profile = PublicProfile::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->find($row->id);

        $seoData = [
            'title' => $course->title . ' — ' . $tenant->name,
            'description' => $course->short_description ?? Str::limit(strip_tags($course->description), 160),
            'canonical' => route('growth.programs.show', [$profileSlug, $courseSlug]),
        ];

        $this->eventService->record([
            'tenant_id' => $tenant->id,
            'event_name' => 'program_viewed',
            'eventable_type' => Course::class,
            'eventable_id' => $course->id,
            'source' => request('source'),
            'campaign' => request('campaign'),
            'metadata' => ['profile_slug' => $profileSlug],
        ]);

        return view('growth.public.program', compact('profile', 'tenant', 'course', 'seoData', 'waitlistCount'));
    }
}
