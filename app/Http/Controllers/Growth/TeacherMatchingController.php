<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Services\TeacherMatchingService;
use Illuminate\Http\Request;

class TeacherMatchingController extends Controller
{
    public function __construct(protected TeacherMatchingService $matchingService) {}

    public function index(Request $request)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        $filters = $request->only(['subject', 'level', 'status', 'search']);
        $opportunities = $this->matchingService->getMatchingOpportunities(
            $teacher->id,
            $filters
        );

        return view('growth.matching.index', compact('opportunities', 'filters'));
    }

    public function show(Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'open', 404);

        $opportunity->load(['tenant', 'demandAggregation', 'matchedCourse']);

        return view('growth.matching.show', compact('opportunity'));
    }

    public function accept(Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'open', 400);

        try {
            $opportunity = app(\App\Services\OpportunityService::class)->acceptOpportunity(
                $opportunity->id,
                auth()->user()->instructor->id
            );

            return redirect()->route('growth.matching.matched')
                ->with('success', __('Opportunity accepted successfully!'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function decline(Request $request, Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'open', 400);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        app(\App\Services\OpportunityService::class)->declineOpportunity(
            $opportunity->id,
            auth()->user()->instructor->id,
            $validated['reason'] ?? null
        );

        return redirect()->route('growth.matching.index')
            ->with('success', __('Opportunity declined'));
    }

    public function matched()
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        $opportunities = $this->matchingService->getMatchedOpportunities($teacher->id);

        return view('growth.matching.matched', compact('opportunities'));
    }

    public function groupForming()
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        $opportunities = $this->matchingService->getGroupFormingOpportunities($teacher->id);

        return view('growth.matching.group-forming', compact('opportunities'));
    }

    public function groupFormed()
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        $opportunities = $this->matchingService->getGroupFormedOpportunities($teacher->id);

        return view('growth.matching.group-formed', compact('opportunities'));
    }

    public function stats()
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        $stats = $this->matchingService->getTeacherStats($teacher->id);

        return view('growth.matching.stats', compact('stats'));
    }
}