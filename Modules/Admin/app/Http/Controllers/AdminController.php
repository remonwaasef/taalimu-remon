<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $totalTenants = \App\Models\Tenant::withoutGlobalScopes()->count();
            $activeTenants = \App\Models\Tenant::withoutGlobalScopes()->where('status', 'active')->count();
            $totalStudents = \App\Models\Student::withoutGlobalScopes()->count();
            $expiringSoon = \App\Models\Subscription::withoutGlobalScopes()
                ->where('ends_at', '<=', now()->addDays(7))
                ->where('ends_at', '>=', now())
                ->count();

            // Financial Metrics
            $totalRevenue = \App\Models\Invoice::withoutGlobalScopes()->where('status', 'paid')->sum('amount') ?? 0;
            $thisMonthRevenue = \App\Models\Invoice::withoutGlobalScopes()
                ->where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0;

            // Support Metrics
            $openTickets = \App\Models\Ticket::withoutGlobalScopes()->where('status', '!=', 'closed')->count();
            $totalTickets = \App\Models\Ticket::withoutGlobalScopes()->count();

            // Recent Tickets
            $recentTickets = \App\Models\Ticket::withoutGlobalScopes()->with('user', 'tenant')->latest()->take(5)->get();

            // Subscription Analytics
            $planAnalytics = \App\Models\Package::withoutGlobalScopes()
                ->where('is_active', true)
                ->withCount(['features'])
                ->get()
                ->map(function ($package) {
                    $activeSubIds = \App\Models\Subscription::withoutGlobalScopes()
                        ->where('status', 'active')
                        ->where(function ($q) use ($package) {
                            $q->where('stripe_price', $package->stripe_price_id)
                                ->orWhere('package_id', $package->id);
                        })
                        ->pluck('id');

                    $centersCount = \App\Models\Tenant::withoutGlobalScopes()
                        ->whereHas('subscriptions', function ($q) use ($activeSubIds) {
                            $q->whereIn('id', $activeSubIds)->where('status', 'active');
                        })->count();

                    $totalProfits = \App\Models\Invoice::withoutGlobalScopes()
                        ->whereIn('subscription_id', $activeSubIds)
                        ->where('status', 'paid')
                        ->sum('amount') ?? 0;

                    return [
                        'name' => $package->name ?? 'Package',
                        'centers_count' => $centersCount,
                        'total_profits' => $totalProfits,
                        'badge' => $package->badge ?? null,
                    ];
                });

            // Recent Tenants
            $recentTenants = \App\Models\Tenant::withoutGlobalScopes()->latest()->take(5)->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin Dashboard Data Loading Failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $totalTenants = $totalTenants ?? 0;
            $activeTenants = $activeTenants ?? 0;
            $totalStudents = $totalStudents ?? 0;
            $expiringSoon = $expiringSoon ?? 0;
            $totalRevenue = $totalRevenue ?? 0;
            $thisMonthRevenue = $thisMonthRevenue ?? 0;
            $openTickets = $openTickets ?? 0;
            $totalTickets = $totalTickets ?? 0;
            $recentTickets = $recentTickets ?? collect([]);
            $recentTenants = $recentTenants ?? collect([]);
            $planAnalytics = $planAnalytics ?? collect([]);
        }

        return view('admin::index', compact(
            'totalTenants',
            'activeTenants',
            'totalStudents',
            'expiringSoon',
            'totalRevenue',
            'thisMonthRevenue',
            'openTickets',
            'totalTickets',
            'recentTickets',
            'recentTenants',
            'planAnalytics'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
