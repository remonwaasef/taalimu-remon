<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DemandAggregation;
use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\OpportunityGroup;
use App\Models\Tenant;
use App\Services\DemandAggregationService;
use App\Services\GroupFormationService;
use App\Services\OpportunityService;
use App\Services\OpportunityScoringService;
use App\Services\TeacherMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OpportunityController extends Controller
{
    public function __construct(
        protected OpportunityService $opportunityService,
        protected TeacherMatchingService $matchingService,
        protected GroupFormationService $groupFormationService,
        protected DemandAggregationService $aggregationService,
        protected OpportunityScoringService $scoringService
    ) {}

    public function index(Request $request)
    {
        $tenant = app('tenant');
        $filters = $request->only(['subject', 'level', 'status', 'search']);
        $opportunities = $this->opportunityService->getOpportunitiesForTenant($tenant->id, $filters);

        return view('growth.opportunities.index', compact('opportunities', 'filters'));
    }

public function create()
{
        $tenant = app('tenant');
        $subjects = DemandRequest::where('tenant_id', app('tenant')->id)
            ->where('status', 'new')
            ->select('subject')
            ->distinct()
            ->pluck('subject');

        return view('growth.opportunities.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
        ]);

        // Aggregate current new demands (optionally narrowed to the subject)
        // into aggregations and generate opportunities from them.
        if ($request->filled('subject')) {
            $pending = DemandRequest::where('tenant_id', app('tenant')->id)
                ->where('status', 'new')
                ->where('subject', $validated['subject'])
                ->exists();

            if (!$pending) {
                return back()->withErrors(['subject' => __('No new demands found for this subject')]);
            }
        }

        $created = $this->aggregationService->aggregateForTenant(app('tenant')->id);

        return redirect()->route('growth.opportunities.index')
            ->with('success', __('Opportunities updated (:count aggregation(s) processed)', ['count' => $created]));
    }

    public function show(Opportunity $opportunity)
    {
        $tenant = app('tenant');
        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);

        $opportunity->load([
            'tenant',
            'demandAggregation',
            'matchedTeacher',
            'matchedCourse',
            'groups.course',
            'groups.enrollments.user.student'
        ]);

        $stats = [
            'demand_volume' => $opportunity->demand_volume,
            'score' => $opportunity->score,
            'score_breakdown' => $opportunity->score_breakdown,
            'matched_teacher' => $opportunity->matchedTeacher,
            'matched_course' => $opportunity->matchedCourse,
            'groups_count' => $opportunity->groups->count(),
            'total_students' => $opportunity->groups->sum('student_count'),
            'groups' => $opportunity->groups->load('course', 'enrollments.user.student'),
            'conversions_count' => \App\Models\DemandAttribution::where('tenant_id', $opportunity->tenant_id)
                ->where('opportunity_id', $opportunity->id)
                ->count(),
            'conversion_rate' => $this->groupFormationService->conversionRateForOpportunity($opportunity),
        ];

        return view('growth.opportunities.show', compact('opportunity', 'stats'));
    }

    public function accept(Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403, 'Only instructors can accept opportunities');

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'open', 400, 'Opportunity is not open for acceptance');

        try {
            $opportunity = $this->matchingService->acceptOpportunity($opportunity->id, $teacher->id);
            return redirect()->back()->with('success', __('Opportunity accepted successfully'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function decline(Request $request, Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'open', 400, 'Opportunity is not open');

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $this->matchingService->declineOpportunity($opportunity->id, $teacher->id, $validated['reason'] ?? null);

        return redirect()->back()->with('success', __('Opportunity declined'));
    }

    public function startGroupFormation(Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'matched', 400);
        abort_unless($opportunity->matched_teacher_id === $teacher->id, 403);

        try {
            $opportunity = $this->matchingService->startGroupFormation($opportunity->id, $teacher->id);
            return redirect()->route('growth.opportunities.show', $opportunity)
                ->with('success', __('Group formation started'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function completeGroupFormation(Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'group_forming', 400);
        abort_unless($opportunity->matched_teacher_id === $teacher->id, 403);

        try {
            $opportunity = $this->matchingService->completeGroupFormation($opportunity->id, $teacher->id);
            return redirect()->route('growth.opportunities.show', $opportunity)
                ->with('success', __('Group formation completed'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function createGroup(Request $request, Opportunity $opportunity)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($opportunity->status === 'matched', 400);
        abort_unless($opportunity->matched_teacher_id === $teacher->id, 403);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        try {
            $group = $this->groupFormationService->createGroup($opportunity, $validated);
            return redirect()->route('growth.opportunities.groups.show', [$opportunity, $group])
                ->with('success', __('Group created successfully'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showGroup(Opportunity $opportunity, OpportunityGroup $group)
    {
        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($group->opportunity_id === $opportunity->id, 404);

        $details = $this->groupFormationService->getGroupDetails($group);

        $students = \App\Models\Student::forTenant(app('tenant')->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'email']);

        return view('growth.opportunities.group', compact('group', 'details', 'students', 'opportunity'));
    }

    public function addStudent(Request $request, Opportunity $opportunity, OpportunityGroup $group)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($group->opportunity_id === $opportunity->id, 404);
        abort_unless($group->opportunity->matched_teacher_id === $teacher->id, 403);
        abort_unless($group->canAddStudent(), 400, 'Group cannot accept more students');

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        try {
            $enrollment = $this->groupFormationService->addStudentToGroup($group, $validated['student_id'], $teacher->id);
            return redirect()->back()->with('success', __('Student added to group'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeStudent(Opportunity $opportunity, OpportunityGroup $group, $studentId)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($group->opportunity_id === $opportunity->id, 404);
        abort_unless($group->opportunity->matched_teacher_id === $teacher->id, 403);

        try {
            $this->groupFormationService->removeStudentFromGroup($group, $studentId);
            return redirect()->back()->with('success', __('Student removed from group'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function completeGroup(Opportunity $opportunity, OpportunityGroup $group)
    {
        $teacher = auth()->user()->instructor;
        abort_unless($teacher, 403);

        abort_unless($opportunity->tenant_id === app('tenant')->id, 403);
        abort_unless($group->opportunity_id === $opportunity->id, 404);
        abort_unless($group->opportunity->matched_teacher_id === $teacher->id, 403);

        try {
            $group = $this->groupFormationService->completeGroup($group);
            return redirect()->route('growth.opportunities.show', $opportunity)
                ->with('success', __('Group formation completed'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}