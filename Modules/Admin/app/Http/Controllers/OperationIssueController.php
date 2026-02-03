<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OperationIssue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = OperationIssue::with(['tenant', 'user', 'assignee'])
            ->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('uuid', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $issues = $query->paginate(20)->withQueryString();

        // Stats for cards
        $stats = [
            'critical' => OperationIssue::where('severity', 'critical')->whereIn('status', ['new', 'acknowledged', 'in_progress'])->count(),
            'open' => OperationIssue::whereIn('status', ['new', 'acknowledged', 'in_progress'])->count(),
            'resolved_today' => OperationIssue::where('status', 'resolved')->whereDate('resolved_at', today())->count(),
            'sla_breached' => OperationIssue::where('sla_breached', true)->whereIn('status', ['new', 'acknowledged', 'in_progress'])->count(),
        ];

        return view('admin::operation_issues.index', compact('issues', 'stats'));
    }

    public function show($uuid)
    {
        $issue = OperationIssue::where('uuid', $uuid)
            ->with(['tenant', 'user', 'timeline.user', 'attachments.user', 'assignee'])
            ->firstOrFail();

        // Mark as acknowledged if seen by admin and status is new
        if ($issue->status === 'new') {
            $issue->acknowledge(auth()->user());
        }

        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'super_admin');
        })->get();

        return view('admin::operation_issues.show', compact('issue', 'admins'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $issue = OperationIssue::where('uuid', $uuid)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:new,acknowledged,in_progress,resolved,closed,wont_fix',
            'comment' => 'nullable|string'
        ]);

        if ($request->status === 'resolved') {
            $issue->resolve('fixed', $request->comment ?? 'Resolved by admin', auth()->user());
        } else {
            $issue->updateStatus($request->status, auth()->user(), $request->comment);
        }

        return back()->with('success', 'Status updated successfully');
    }

    public function assign(Request $request, $uuid)
    {
        $issue = OperationIssue::where('uuid', $uuid)->firstOrFail();
        
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $assignee = User::findOrFail($request->assigned_to);
        $issue->assignTo($assignee, auth()->user());

        return back()->with('success', 'Issue assigned successfully');
    }

    public function addComment(Request $request, $uuid)
    {
        $issue = OperationIssue::where('uuid', $uuid)->firstOrFail();
        
        $request->validate([
            'comment' => 'required|string'
        ]);

        $issue->addComment($request->comment, auth()->user());

        return back()->with('success', 'Comment added successfully');
    }
}
