<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $this->authorize('manage settings', \App\Models\Tenant::class);

        $activities = Activity::with('causer')
            ->where(function ($query) {
                // Activities performed on models scoped to this tenant
                $query->whereHasMorph('subject', [\App\Models\User::class, \App\Models\Student::class, \App\Models\Course::class], function ($q) {
                    $q->where('tenant_id', app('tenant')->id);
                })
                // OR activities caused by users of this tenant
                ->orWhereHas('causer', function ($q) {
                    $q->where('tenant_id', app('tenant')->id);
                });
            })
            ->latest()
            ->paginate(20);

        return view('center::activity_logs.index', compact('activities'));
    }
}
