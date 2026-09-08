<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Services\GrowthEventService;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WaitlistController extends Controller
{
    public function __construct(
        protected WaitlistService $waitlistService,
        protected GrowthEventService $eventService
    ) {}

    public function store(Request $request, string $profileSlug, string $courseSlug)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $row = DB::table('public_profiles')
            ->where('slug', $profileSlug)
            ->where('published', true)
            ->first();

        if (! $row) {
            abort(404);
        }

        $tenant = Tenant::find($row->tenant_id);

        app()->instance('tenant', $tenant);

        $course = Course::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('tenant_id', $tenant->id)
            ->where('slug', $courseSlug)
            ->where('published', true)
            ->firstOrFail();

        if (! $course->isFull()) {
            throw ValidationException::withMessages([
                'course' => 'This course is not full. You can register directly.',
            ]);
        }

        $waitlist = $this->waitlistService->joinWaitlist(
            $course,
            $validated + ['user_id' => auth()->id()],
            $request->source
        );

        $this->eventService->record([
            'tenant_id' => $tenant->id,
            'event_name' => 'waitlist_joined',
            'eventable_type' => Course::class,
            'eventable_id' => $course->id,
            'source' => $request->source,
            'campaign' => $request->campaign,
            'metadata' => [
                'position' => $waitlist->position,
                'student_name' => $validated['name'],
            ],
        ]);

        return back()->with('success', 'You have been added to the waiting list. Position: #' . $waitlist->position);
    }
}
