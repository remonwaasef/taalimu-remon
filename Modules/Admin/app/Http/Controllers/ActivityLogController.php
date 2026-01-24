<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Authorization: Only super admins can view activity logs
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $activities = Activity::with(['causer', 'subject'])
            ->latest()
            ->paginate(20);

        return view('admin::activity_logs.index', compact('activities'));
    }
}
