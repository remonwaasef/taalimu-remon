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
        $totalTenants = \App\Models\Tenant::count();
        $activeTenants = \App\Models\Tenant::where('status', 'active')->count();
        $totalStudents = \App\Models\Student::count();
        $expiringSoon = \App\Models\Subscription::where('ends_at', '<=', now()->addDays(7))
            ->where('ends_at', '>=', now())
            ->count();

        // Financial Metrics
        $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('amount');
        $thisMonthRevenue = \App\Models\Invoice::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Support Metrics
        $openTickets = \App\Models\Ticket::where('status', '!=', 'closed')->count();
        $totalTickets = \App\Models\Ticket::count();

        // Recent Tickets
        $recentTickets = \App\Models\Ticket::with('user', 'tenant')->latest()->take(5)->get();

        // Subscription Analytics
        $planAnalytics = \App\Models\Package::where('is_active', true)
            ->withCount(['features'])
            ->get()
            ->map(function ($package) {
                $activeSubIds = \App\Models\Subscription::where('status', 'active')
                    ->where(function ($q) use ($package) {
                        $q->where('stripe_price', $package->stripe_price_id)
                            ->orWhere('package_id', $package->id);
                    })
                    ->pluck('id');

                $centersCount = \App\Models\Tenant::whereHas('subscriptions', function ($q) use ($activeSubIds) {
                    $q->whereIn('id', $activeSubIds)->where('status', 'active');
                })->count();

                $totalProfits = \App\Models\Invoice::whereIn('subscription_id', $activeSubIds)
                    ->where('status', 'paid')
                    ->sum('amount');

                return [
                    'name' => $package->name,
                    'centers_count' => $centersCount,
                    'total_profits' => $totalProfits,
                    'badge' => $package->badge,
                ];
            });

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
