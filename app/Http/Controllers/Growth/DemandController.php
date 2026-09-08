<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Tenant;
use App\Services\DemandService;
use App\Services\GrowthEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandController extends Controller
{
    public function __construct(
        protected DemandService $demandService,
        protected GrowthEventService $eventService
    ) {}

    public function show(string $slug)
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

        $profile = \App\Models\PublicProfile::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->find($row->id);

        $seoData = [
            'title' => __('Request a Program') . ' — ' . $tenant->name,
            'description' => __('Can not find what you are looking for? Tell us what you need.'),
            'canonical' => route('growth.demand.show', $slug),
        ];

        return view('growth.public.demand', compact('profile', 'tenant', 'seoData'));
    }

    public function store(Request $request, string $slug)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:100',
            'preferred_days' => 'nullable|string|max:100',
            'preferred_time' => 'nullable|string|max:100',
            'delivery_mode' => 'nullable|in:online,offline,both',
            'location' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:1000',
        ]);

        $row = DB::table('public_profiles')
            ->where('slug', $slug)
            ->where('published', true)
            ->first();

        if (! $row) {
            abort(404);
        }

        $tenant = Tenant::find($row->tenant_id);

        app()->instance('tenant', $tenant);

        $demand = $this->demandService->createDemandRequest(
            array_merge($validated, [
                'tenant_id' => $tenant->id,
                'user_id' => auth()->id(),
            ]),
            $request->source,
            $request->campaign
        );

        $this->eventService->record([
            'tenant_id' => $tenant->id,
            'event_name' => 'demand_submitted',
            'eventable_type' => DemandRequest::class,
            'eventable_id' => $demand->id,
            'source' => $request->source,
            'campaign' => $request->campaign,
            'metadata' => [
                'subject' => $demand->subject,
                'student_name' => $demand->name,
            ],
        ]);

        return back()->with('success', 'Your request has been submitted. We will contact you soon.');
    }
}
