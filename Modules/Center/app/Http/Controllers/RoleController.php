<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\Services\PermissionService;

class RoleController extends Controller
{
    protected $permissionService;
    protected $roleRepository;

    public function __construct(PermissionService $permissionService, \App\Repositories\RoleRepository $roleRepository)
    {
        $this->permissionService = $permissionService;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        
        // Use Repository to fetch merged Global + Local roles with Caching
        $roles = $this->roleRepository->getAllForTenant(app('tenant')->id);

        return view('center::roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        $permissions = $this->permissionService->getGroupedPermissions();
        return view('center::roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        
        $request->validate([
            'name' => [
                                'nullable', 
 
                'string', 
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('tenant_id', app('tenant')->id)
                                 ->orWhereNull('tenant_id');
                })
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name, 
            'guard_name' => 'web',
            'tenant_id' => app('tenant')->id
        ]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_070'));
    }

    public function edit($id)
    {
        // Use Repository or Direct Find. We need to check if it's editable.
        // Even if we find a Global Role, the Policy should block 'update'.
        $role = Role::where('tenant_id', app('tenant')->id)->findOrFail($id);
        
        $this->authorize('update', $role); // This will throw 403 if it's a System Role
        
        $permissions = $this->permissionService->getGroupedPermissions();
        
        return view('center::roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $role);
        
        $request->validate([
            'name' => [
                                'nullable', 
 
                'string', 
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('tenant_id', app('tenant')->id)
                                 ->orWhereNull('tenant_id');
                })->ignore($id)
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_071'));
    }

    public function destroy($id)
    {
        $role = Role::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $role);
        
        if ($role->users()->count() > 0) {
            return back()->with('error', __('center::messages.msg_072'));
        }

        $role->delete();

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_073'));
    }
}
