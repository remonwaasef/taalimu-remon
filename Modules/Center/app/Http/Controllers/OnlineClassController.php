<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OnlineClass;
use Illuminate\Http\Request;

class OnlineClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id;

        $query = OnlineClass::where('tenant_id', $tenantId)
            ->with(['instructor', 'course']);

        if ($request->has('instructor_id') && $request->instructor_id != '') {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('start_time', $request->date);
        }

        $onlineClasses = $query->latest('start_time')->paginate(15);
        $onlineClasses->appends($request->all());

        // Overview Stats
        $stats = [
            'total' => OnlineClass::where('tenant_id', $tenantId)->count(),
            'scheduled' => OnlineClass::where('tenant_id', $tenantId)->where('status', 'scheduled')->count(),
            'completed' => OnlineClass::where('tenant_id', $tenantId)->where('status', 'completed')->count(),
            'in_progress' => OnlineClass::where('tenant_id', $tenantId)->where('status', 'in_progress')->count(),
        ];

        // Fetch instructors for the filter dropdown
        $instructors = \App\Models\User::where('tenant_id', $tenantId)
            ->whereHas('instructor')
            ->get();

        return view('center::online_classes.index', compact('onlineClasses', 'stats', 'instructors'));
    }

    /**
     * Force delete / cancel a class (Admin action)
     */
    public function destroy($id)
    {
        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id;
        $class = OnlineClass::where('tenant_id', $tenantId)->findOrFail($id);

        $class->delete();

        return back()->with('success', __('center::messages.deleted_successfully') ?? 'تم حذف الجلسة بنجاح.');
    }
}
