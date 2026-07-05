<?php

namespace Modules\Center\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;

class UserController extends Controller
{
    protected $roleRepository;

    public function __construct(\App\Repositories\RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $query = User::where('tenant_id', $this->tenant->id)
            ->whereNotIn('role', ['student', 'instructor']); // Show all admin/staff roles, only exclude students and instructors handled in other modules

        if ($request->has('search')) {
            $search = \App\Helpers\QueryHelper::escapeLike($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $users = $query->latest()->paginate(10);

        return view('center::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        // Fetch ALL available roles (System + Custom) via Repository
        $roles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->filter(function ($role) {
                return $role->name !== 'super_admin';
            });

        $permissions = \Spatie\Permission\Models\Permission::where('name', 'not like', '%centers%')
            ->where('name', 'not like', 'admin.%')
            ->get();

        $rolePermissions = $roles->mapWithKeys(function ($role) {
            return [$role->name => $role->permissions->pluck('name')->toArray()];
        });

        return view('center::users.create', compact('roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Store a newly created team member.
     *
     * الأتمتة الكاملة:
     * 1. توليد كلمة مرور آمنة تلقائياً (إذا لم تُحدد)
     * 2. إرسال بيانات الدخول بالإيميل تلقائياً
     * 3. فرض تغيير كلمة المرور عند أول دخول
     * 4. إشعار المدير عبر Telegram
     * 5. تسجيل العملية في Activity Log
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        // Get valid role names from Repository
        $validRoles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->pluck('name')
            ->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where('tenant_id', $this->tenant->id),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', Rule::in($validRoles)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Auto-generate secure password if not provided
        $plainPassword = $validated['password'] ?? \Illuminate\Support\Str::random(12);

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($plainPassword),
            'must_change_password' => true, // فرض تغيير كلمة المرور عند أول دخول
            'email_verified_at' => now(),
        ]);

        $user->role = $validated['role'];
        $user->tenant_id = $this->tenant->id;
        $user->save();

        // Assign Spatie role
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);
        $user->assignRole($validated['role']);

        // Sync extra permissions if provided
        if (! empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        // Auto-send welcome email with credentials (via Queue)
        try {
            $loginUrl = route('center.login', ['tenant' => $this->tenant->domain]);
            $roleLabel = __('roles.'.$validated['role'], [], app()->getLocale());
            $user->notify(new \App\Notifications\TeamMemberWelcome(
                $this->tenant->name,
                $loginUrl,
                $roleLabel
            ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send team welcome email: '.$e->getMessage());
        }

        // Telegram alert to admin
        try {
            app(\App\Services\TelegramService::class)->sendAdminNotification(
                "<b>👥 عضو فريق جديد</b>\n\n".
                "<b>🏢 المركز:</b> {$this->tenant->name}\n".
                "<b>👤 الاسم:</b> {$user->name}\n".
                "<b>📧 البريد:</b> <code>{$user->email}</code>\n".
                "<b>🔑 الدور:</b> {$validated['role']}\n".
                '<b>➕ أضافه:</b> '.auth()->user()->name
            );
        } catch (\Throwable $e) {
            // Silent fail — Telegram is non-critical
        }

        // Activity log
        activity('team')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'role' => $validated['role'],
                'ip' => request()->ip(),
            ])
            ->log('Team member added: '.$user->name);

        return redirect()->route('center.users.index', ['tenant' => $this->tenant->domain])
            ->with('success', __('تم إضافة العضو وإرسال بيانات الدخول تلقائياً.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('update', $user);

        $roles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->filter(function ($role) {
                return $role->name !== 'super_admin';
            });

        $permissions = \Spatie\Permission\Models\Permission::where('name', 'not like', '%centers%')
            ->where('name', 'not like', 'admin.%')
            ->get();

        $rolePermissions = $roles->mapWithKeys(function ($role) {
            return [$role->name => $role->permissions->pluck('name')->toArray()];
        });

        return view('center::users.edit', compact('user', 'roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('update', $user);

        // Get valid role names from Repository
        $validRoles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->pluck('name')
            ->toArray();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)->where('tenant_id', $this->tenant->id)],
            'password' => [
                'nullable',
                'string',
                'confirmed',
            ],
            'role' => ['nullable', Rule::in($validRoles)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->name = $validated['name'] ?? $user->name;
        $user->email = $validated['email'] ?? $user->email;

        if (! empty($validated['role']) && $user->role !== $validated['role']) {
            $user->role = $validated['role'];
            $user->syncRoles([$validated['role']]);
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncPermissions($request->input('permissions', []));

        // Activity log
        activity('team')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['role' => $user->role, 'ip' => request()->ip()])
            ->log('Team member updated: '.$user->name);

        return redirect()->route('center.users.index')
            ->with('success', __('تم تحديث بيانات العضو بنجاح.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', __('لا يمكنك حذف حسابك الشخصي.'));
        }

        $userName = $user->name;
        $userRole = $user->role;
        $user->delete();

        // Activity log
        activity('team')
            ->causedBy(auth()->user())
            ->withProperties(['deleted_user' => $userName, 'role' => $userRole, 'ip' => request()->ip()])
            ->log('Team member removed: '.$userName);

        // Telegram alert
        try {
            app(\App\Services\TelegramService::class)->sendAdminNotification(
                "<b>🚫 حذف عضو فريق</b>\n\n".
                "<b>🏢 المركز:</b> {$this->tenant->name}\n".
                "<b>👤 العضو المحذوف:</b> {$userName} ({$userRole})\n".
                '<b>❌ حذفه:</b> '.auth()->user()->name
            );
        } catch (\Throwable $e) {
        }

        return redirect()->route('center.users.index')
            ->with('success', __('تم حذف العضو من الفريق.'));
    }

    /**
     * Show the authenticated user's profile.
     */
    public function profile()
    {
        $user = auth()->user();

        return view('center::users.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)->where('tenant_id', auth()->user()->tenant_id)],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', __('center::messages.msg_078'));
    }
}
