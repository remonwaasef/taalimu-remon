<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        // Only show System Roles (NULL tenant_id) in the central admin area
        $roles = Role::with(['permissions', 'users'])
            ->where('guard_name', 'web')
            ->whereNull('tenant_id')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin::roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Role::class);
        $permissions = $this->permissionService->getGroupedPermissions();

        return view('admin::roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->where(fn ($q) => $q->whereNull('tenant_id')),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [], [
            'name' => __('admin.roles.role_name'),
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web', 'tenant_id' => null]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', __('Role created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        $permissions = $this->permissionService->getGroupedPermissions();

        return view('admin::roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);

        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($id)->where(fn ($q) => $q->whereNull('tenant_id')),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [], [
            'name' => __('admin.roles.role_name'),
        ]);

        $role->update(['name' => $request->name]);

        // Prevent renaming core system roles which are hardcoded in policies/logic
        $systemRoles = ['super_admin', 'center_admin', 'instructor', 'student', 'secretary', 'accountant', 'staff'];
        if (in_array($role->getOriginal('name'), $systemRoles) && $request->name !== $role->getOriginal('name')) {
            return back()->with('error', __('Cannot rename a core system role. You can only modify its permissions.'));
        }

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);

            // Professional Sync: Propagate changes to all tenant-specific roles with the same name
            // This ensures that existing centers get updated permissions for global role clones (if any exist)
            $tenantRoles = Role::where('name', $role->name)
                ->whereNotNull('tenant_id')
                ->limit(500)
                ->get();

            foreach ($tenantRoles as $tenantRole) {
                $tenantRole->syncPermissions($request->permissions);
            }
        }

        return redirect()->route('admin.roles.index')->with('success', __('Role updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);

        $systemRoles = ['super_admin', 'center_admin', 'instructor', 'student', 'secretary', 'accountant', 'staff'];
        if (in_array($role->name, $systemRoles)) {
            return back()->with('error', __('Cannot delete core system roles.'));
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', __('Cannot delete role because it is assigned to users.'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('Role deleted successfully.'));
    }
}
