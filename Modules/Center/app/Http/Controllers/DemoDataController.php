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
    }

    public function seed(Request $request)
    {
        $tenant = app('tenant');
        
        try {
            $this->demoService->seedForTenant($tenant);
            
            // Clear dashboard cache to show new data immediately
            \App\Support\TenantCache::forget("dashboard_stats_v3");
            \App\Support\TenantCache::forget("recent_activities");
            
            return redirect()->route('center.dashboard')->with('success', 'تمت إضافة البيانات التجريبية بنجاح! 🎉 استكشف التقارير والرسوم البيانية الآن.');
        } catch (\Exception $e) {
            \Log::error('Demo Seeding Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة البيانات التجريبية: ' . $e->getMessage());
        }
    }
}
