<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DemoDataService;
use Illuminate\Http\Request;

class DemoDataController extends Controller
{
    protected $demoService;

    public function __construct(DemoDataService $demoService)
    {
        $this->demoService = $demoService;
        $this->middleware(function ($request, $next) {
            abort_if(! auth()->user() || ! auth()->user()->hasRole('center_admin'), 403, 'Unauthorized action.');

            return $next($request);
        });
    }

    public function seed(Request $request)
    {
        $tenant = app('tenant');

        try {
            $this->demoService->seedForTenant($tenant);

            // Clear dashboard cache to show new data immediately
            \App\Support\TenantCache::forget('dashboard_stats_v3');
            \App\Support\TenantCache::forget('recent_activities');

            return redirect()->route('center.dashboard')->with('success', 'تمت إضافة البيانات التجريبية بنجاح! 🎉 استكشف التقارير والرسوم البيانية الآن.');
        } catch (\Exception $e) {
            \Log::error('Demo Seeding Failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة البيانات التجريبية.');
        }
    }

    public function destroy(Request $request)
    {
        $tenant = app('tenant');

        try {
            $this->demoService->removeDemoDataForTenant($tenant);

            // Clear dashboard cache to show clean state immediately
            \App\Support\TenantCache::forget('dashboard_stats_v3');
            \App\Support\TenantCache::forget('recent_activities');

            return redirect()->route('center.dashboard')->with('success', 'تم حذف البيانات التجريبية بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Demo Seeding Reset Failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف البيانات التجريبية.');
        }
    }
}
