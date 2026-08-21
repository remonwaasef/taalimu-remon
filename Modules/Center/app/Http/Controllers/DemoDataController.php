<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DemoDataService;
use App\Traits\ClearsDashboardCache;
use Illuminate\Http\Request;

class DemoDataController extends Controller
{
    use ClearsDashboardCache;

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
            static::clearDashboardCache();

            return redirect()->route('center.dashboard', ['tenant' => $tenant->domain])
                ->with('success', __('center::dashboard.demo_seed_success'));
        } catch (\Exception $e) {
            \Log::error('Demo Seeding Failed: '.$e->getMessage());

            return redirect()->route('center.dashboard', ['tenant' => $tenant->domain])
                ->with('error', __('center::dashboard.demo_seed_error'));
        }
    }

    public function destroy(Request $request)
    {
        $tenant = app('tenant');

        try {
            $this->demoService->removeDemoDataForTenant($tenant);

            // Clear dashboard cache to show clean state immediately
            static::clearDashboardCache();

            return redirect()->route('center.dashboard', ['tenant' => $tenant->domain])
                ->with('success', __('center::dashboard.demo_destroy_success'));
        } catch (\Exception $e) {
            \Log::error('Demo Seeding Reset Failed: '.$e->getMessage());

            return redirect()->route('center.dashboard', ['tenant' => $tenant->domain])
                ->with('error', __('center::dashboard.demo_destroy_error'));
        }
    }
}

