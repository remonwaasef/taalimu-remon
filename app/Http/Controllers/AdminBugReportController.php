<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;

class AdminBugReportController extends Controller
{
    /**
     * Display a listing of the bug reports for super admins.
     */
    public function index(Request $request)
    {
        $query = BugReport::with(['tenant', 'user'])->latest();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        $reports = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => BugReport::count(),
            'open' => BugReport::where('status', 'open')->count(),
            'in_progress' => BugReport::where('status', 'in_progress')->count(),
            'resolved' => BugReport::where('status', 'resolved')->count(),
            'critical' => BugReport::where('priority', 'critical')->count(),
        ];

        return view('admin.bug_reports.index', compact('reports', 'stats'));
    }

    /**
     * Update the status of a bug report.
     */
    public function updateStatus(Request $request, BugReport $bugReport)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if ($request->status === 'resolved' && $bugReport->status !== 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $bugReport->update($updateData);

        return back()->with('success', 'تم تحديث حالة البلاغ بنجاح.');
    }
}
