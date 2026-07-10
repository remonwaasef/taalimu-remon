<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(\App\Services\TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Tenant::class);
        $query = Tenant::query();

        // Basic Filters
        if ($request->filled('search')) {
            $search = \App\Helpers\QueryHelper::escapeLike($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('domain', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Subscription Filters
        if ($request->filled('subscription_status')) {
            if ($request->subscription_status === 'active') {
                $query->whereHas('currentSubscription');
            } elseif ($request->subscription_status === 'expired') {
                $query->whereDoesntHave('currentSubscription', function ($q) {
                    $q->where('status', 'active')->where(function ($sq) {
                        $sq->whereNull('ends_at')->orWhere('ends_at', '>', now());
                    });
                });
            }
        }

        if ($request->filled('package_id')) {
            $query->whereHas('subscriptions', function ($q) use ($request) {
                $q->where('package_id', $request->package_id)->orWhere('stripe_price', $request->package_id);
            });
        }

        // Statistics
        $stats = [
            'total_count' => Tenant::count(),
            'active_count' => Tenant::where('status', 'active')->count(),
            'inactive_count' => Tenant::where('status', 'inactive')->count(),
            'total_students' => \App\Models\Student::count(),
            'active_subscriptions' => \App\Models\Subscription::where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                })->count(),
            'expiring_soon' => \App\Models\Subscription::whereNotNull('ends_at')
                ->whereBetween('ends_at', [now(), now()->addDays(7)])
                ->count(),
        ];

        $tenants = $query->with(['users', 'currentSubscription.package'])
            ->withCount('students')
            ->withSum(['invoices as ltv' => function ($query) {
                $query->where('status', 'paid');
            }], 'amount')
            ->addSelect(['last_activity_at' => \Spatie\Activitylog\Models\Activity::select('activity_log.created_at')
                ->join('users', 'activity_log.causer_id', '=', 'users.id')
                ->whereColumn('users.tenant_id', 'tenants.id')
                ->where('activity_log.causer_type', \App\Models\User::class)
                ->orderBy('activity_log.created_at', 'desc')
                ->limit(1),
            ])
            ->orderBy('tenants.created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $packages = \App\Models\Package::where('is_active', true)->get();

        return view('admin::tenants.index', compact('tenants', 'stats', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Tenant::class);

        return view('admin::tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Tenant::class);
        $request->validate([
            'name' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants,domain',
            'status' => 'nullable|in:active,inactive',
        ]);

        Tenant::forceCreate([
            'name' => $request->name,
            'domain' => $request->domain,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'تم إضافة المركز بنجاح');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $tenant = Tenant::with(['users', 'subscriptions'])->findOrFail($id);
        $this->authorize('view', $tenant);

        $stats = $this->tenantService->getTenantStats($tenant);
        $activities = $this->tenantService->getRecentActivity($tenant);
        $staff = $this->tenantService->getStaff($tenant);
        $subscriptionHistory = $this->tenantService->getSubscriptionHistory($tenant);
        $growthData = $this->tenantService->getGrowthData($tenant);

        return view('admin::tenants.show', array_merge([
            'tenant' => $tenant,
            'activities' => $activities,
            'staff' => $staff,
            'subscriptionHistory' => $subscriptionHistory,
            'growthData' => $growthData,
        ], $stats));
    }

    /**
     * Toggle tenant status.
     */
    public function toggleStatus($id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('update', $tenant);

        $tenant->update([
            'status' => $tenant->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'تم تغيير حالة المركز بنجاح.');
    }

    /**
     * Impersonate a tenant's admin user.
     */
    public function impersonate($id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('impersonate', $tenant);

        $admin = $this->tenantService->getImpersonationUser($tenant);

        if (! $admin) {
            return back()->with('error', 'لا يوجد مستخدم مسؤول متاح لهذا المركز حالياً.');
        }

        // Store original admin ID to allow return
        session(['impersonator_id' => auth()->id()]);

        auth()->login($admin);

        // Redirect to tenant's dashboard on their subdomain
        $protocol = request()->secure() ? 'https://' : 'http://';
        $domain = $tenant->domain.'.'.config('app.tenant_domain');
        $port = (request()->getPort() && ! in_array(request()->getPort(), [80, 443])) ? ':'.request()->getPort() : '';

        return redirect($protocol.$domain.$port.'/dashboard');
    }

    /**
     * Stop impersonating a tenant admin.
     * Uses a one-time token stored in cache to perform the login on the CENTRAL domain.
     * This avoids the cross-domain session problem (sessions are per-domain).
     */
    public function stopImpersonating()
    {
        $centralUrl = config('app.url');

        if (! session()->has('impersonator_id')) {
            return redirect()->to($centralUrl.'/admin')->with('error', 'لا توجد جلسة انتحال شخصية نشطة.');
        }

        $adminId = session()->pull('impersonator_id');

        // Log out the impersonated user from this (tenant) domain's session
        auth()->logout();

        // Generate a secure one-time token and store it in cache for 2 minutes
        $token = \Illuminate\Support\Str::random(64);
        \Illuminate\Support\Facades\Cache::put("impersonation_return:{$token}", $adminId, 120);

        // Redirect to the central domain's return route with the token
        // The admin will be authenticated THERE, not on the tenant subdomain.
        return redirect()->to($centralUrl.'/admin/impersonate/return?token='.$token);
    }

    /**
     * Complete the impersonation stop by authenticating the admin on the central domain.
     * This is called on taalimu.com (central domain), not on the tenant subdomain.
     */
    public function returnFromImpersonation(\Illuminate\Http\Request $request)
    {
        $token = $request->query('token');
        $centralUrl = config('app.url');

        if (! $token) {
            return redirect()->to($centralUrl.'/admin/login')->with('error', 'رمز العودة غير صالح.');
        }

        $cacheKey = "impersonation_return:{$token}";
        $adminId = \Illuminate\Support\Facades\Cache::pull($cacheKey);

        if (! $adminId) {
            return redirect()->to($centralUrl.'/admin/login')->with('error', 'انتهت صلاحية رمز العودة أو تم استخدامه مسبقاً.');
        }

        $admin = \App\Models\User::find($adminId);

        if (! $admin) {
            return redirect()->to($centralUrl.'/admin/login')->with('error', 'المشرف غير موجود.');
        }

        // Login the admin on the CENTRAL domain's session
        auth()->login($admin);
        session()->regenerate();

        return redirect()->to($centralUrl.'/admin/tenants')
            ->with('success', 'تم العودة للوحة تحكم المشرف العام بنجاح.');
    }

    /**
     * Update admin notes for a tenant.
     */
    public function updateNotes(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('updateNotes', $tenant);
        $tenant->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'تم تحديث الملاحظات الإدارية بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('update', $tenant);

        return view('admin::tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('update', $tenant);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants,domain,'.$id,
            'status' => 'nullable|in:active,inactive',
        ]);

        $tenant->update([
            'name' => $request->name,
            'domain' => $request->domain,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'تم تحديث المركز بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->authorize('delete', $tenant);

        // Check if tenant has related data
        $studentsCount = \App\Models\Student::where('tenant_id', $id)->count();

        if ($studentsCount > 0) {
            return back()->withErrors(['message' => 'لا يمكن حذف المركز لأنه يحتوي على '.$studentsCount.' طالب.']);
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'تم حذف المركز بنجاح');
    }

    public function resetPassword(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        $tenant = Tenant::with('users')->findOrFail($id);
        $this->authorize('resetPassword', $tenant);
        $admin = $tenant->users->first();

        if (! $admin) {
            return back()->withErrors(['message' => 'لم يتم العثور على مسؤول لهذا المركز.']);
        }

        $admin->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }
}
