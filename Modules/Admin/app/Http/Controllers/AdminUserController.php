<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    /**
     * Display all admin-level users (tenant_id = null, non-center roles).
     */
    public function index(Request $request)
    {
        $query = User::whereNull('tenant_id')
            ->whereIn('role', $this->adminRoles())
            ->with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $roles = Role::whereNull('tenant_id')
            ->whereIn('name', $this->adminRoles())
            ->get();

        return view('admin::users.index', compact('users', 'roles'));
    }

    /**
     * Show form to create a new admin user.
     */
    public function create()
    {
        $roles = Role::whereNull('tenant_id')
            ->whereIn('name', $this->adminRoles())
            ->get();

        return view('admin::users.create', compact('roles'));
    }

    /**
     * Store a new admin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'role' => ['required', Rule::in($this->adminRoles())],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tenant_id' => null,
            'email_verified_at' => now(),
        ]);

        // Assign Spatie role (no team scope for central admin)
        $role = Role::where('name', $request->role)->whereNull('tenant_id')->first();
        if ($role) {
            $user->assignRole($role);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * Show form to edit an admin user.
     */
    public function edit(User $user)
    {
        abort_if($user->tenant_id !== null, 404);

        $roles = Role::whereNull('tenant_id')
            ->whereIn('name', $this->adminRoles())
            ->get();

        return view('admin::users.edit', compact('user', 'roles'));
    }

    /**
     * Update an admin user.
     */
    public function update(Request $request, User $user)
    {
        abort_if($user->tenant_id !== null, 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
            'role' => ['required', Rule::in($this->adminRoles())],
        ]);

        // Prevent demoting the last super_admin
        if ($user->role === 'super_admin' && $request->role !== 'super_admin') {
            $superAdminCount = User::whereNull('tenant_id')->where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'لا يمكن تغيير دور المشرف العام الأخير.');
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sync Spatie role
        $role = Role::where('name', $request->role)->whereNull('tenant_id')->first();
        if ($role) {
            $user->syncRoles([$role]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * Delete an admin user.
     */
    public function destroy(User $user)
    {
        abort_if($user->tenant_id !== null, 404);

        // Prevent deleting the last super_admin
        if ($user->role === 'super_admin') {
            $superAdminCount = User::whereNull('tenant_id')->where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'لا يمكن حذف المشرف العام الأخير.');
            }
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * The roles that are considered admin-level (central, not tenant-specific).
     * Dynamically fetched from DB: all roles with tenant_id = null, excluding center-only roles.
     */
    protected function adminRoles(): array
    {
        // Center-only roles should NOT be assignable to admin panel users
        $centerOnlyRoles = ['center_admin', 'instructor', 'student', 'secretary', 'accountant', 'staff'];

        return Role::whereNull('tenant_id')
            ->whereNotIn('name', $centerOnlyRoles)
            ->pluck('name')
            ->toArray();
    }
}
