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
        $totalTenants = \App\Models\Tenant::withoutGlobalScopes()->count();
        $activeTenants = \App\Models\Tenant::withoutGlobalScopes()->where('status', 'active')->count();
        $totalStudents = \App\Models\Student::withoutGlobalScopes()->count();
        $expiringSoon = \App\Models\Subscription::withoutGlobalScopes()
            ->where('ends_at', '<=', now()->addDays(7))
            ->where('ends_at', '>=', now())
            ->count();

        $totalRevenue = \App\Models\Invoice::withoutGlobalScopes()->where('status', 'paid')->sum('amount') ?? 0;
        $thisMonthRevenue = \App\Models\Invoice::withoutGlobalScopes()
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;

        $openTickets = \App\Models\Ticket::withoutGlobalScopes()->where('status', '!=', 'closed')->count();
        $totalTickets = \App\Models\Ticket::withoutGlobalScopes()->count();
        $recentTickets = \App\Models\Ticket::withoutGlobalScopes()->with('user', 'tenant')->latest()->take(5)->get();

        $planAnalytics = collect([]);
        try {
            $planAnalytics = \App\Models\Package::withoutGlobalScopes()
                ->where('is_active', true)
                ->get()
                ->map(function ($package) {
                    return [
                        'name' => $package->name ?? 'Package',
                        'centers_count' => 0,
                        'total_profits' => 0,
                        'badge' => $package->badge ?? null,
                    ];
                });
        } catch (\Throwable $e) {
            // Silently fail on plan analytics
        }

        $recentTenants = \App\Models\Tenant::withoutGlobalScopes()->latest()->take(5)->get();

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
