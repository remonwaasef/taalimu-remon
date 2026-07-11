<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Authorization: Only super admins can view subscriptions
        if (! auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Subscription::with(['tenant.users', 'package']);

        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tenant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                });
            } elseif ($request->status === 'expired') {
                $query->whereNotNull('ends_at')->where('ends_at', '<=', now());
            }
        }

        // Statistics
        $stats = [
            'total_count' => Subscription::count(),
            'active_count' => Subscription::where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })->count(),
            'expiring_soon' => Subscription::whereNotNull('ends_at')
                ->whereBetween('ends_at', [now(), now()->addDays(7)])
                ->count(),
            'total_revenue' => \App\Models\Invoice::where('status', 'paid')->sum('amount') / 100, // Assuming stripe amount is in cents
        ];

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        return view('admin::subscriptions.index', compact('subscriptions', 'stats'));
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return back()->with('success', 'تم حذف الاشتراك بنجاح.');
    }

    public function edit($id)
    {
        if (! auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $subscription = Subscription::with('tenant', 'package')->findOrFail($id);
        $packages = Package::where('is_active', true)->get();

        return view('admin::subscriptions.edit', compact('subscription', 'packages'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $subscription = Subscription::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,inactive,expired',
            'package_id' => 'nullable|exists:packages,id',
            'billing_cycle' => 'required|in:monthly,term,yearly',
            'base_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'ends_at' => 'nullable|date',
        ]);

        $subscription->update([
            'status' => $request->status,
            'stripe_status' => $request->status === 'inactive' ? 'canceled' : 'active',
            'package_id' => $request->package_id,
            'billing_cycle' => $request->billing_cycle,
            'base_price' => $request->base_price,
            'total_amount' => $request->total_amount,
            'ends_at' => $request->ends_at,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'تم تحديث بيانات الاشتراك بنجاح.');
    }
}
