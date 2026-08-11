<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Services\PermissionService;
use App\Services\RolePresetService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    protected $permissionService;

    protected $roleRepository;

    protected $presetService;

    public function __construct(
        PermissionService $permissionService,
        RoleRepository $roleRepository,
        RolePresetService $presetService
    ) {
        $this->permissionService = $permissionService;
        $this->roleRepository = $roleRepository;
        $this->presetService = $presetService;
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

        // Only tenant-assignable permissions are shown — system groups never appear
        $permissions = $this->permissionService->getTenantGroupedPermissions();
        $presets = $this->presetService->presets();

        return view('center::roles.create', compact('permissions', 'presets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $names = array_map('strtolower', \App\Models\User::RESERVED_ROLE_NAMES);
                    if (in_array(strtolower(trim((string) $value)), $names, true)) {
                        $fail(__('The role name is reserved and cannot be used.'));
                    }
                },
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('tenant_id', app('tenant')->id)
                        ->orWhereNull('tenant_id');
                }),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'tenant_id' => app('tenant')->id,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($this->safePermissions($request->permissions));
        }

        $this->roleRepository->clearCache(app('tenant')->id);

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_070'));
    }

    public function edit($id)
    {
        $role = $this->roleRepository->findForTenant($id, app('tenant')->id);

        abort_unless($role, 404);

        // System roles render the same page in read-only mode (see edit.blade.php)
        $this->authorize('view', $role);

        // Only tenant-assignable permissions are shown — system groups never appear
        $permissions = $this->permissionService->getTenantGroupedPermissions();

        return view('center::roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = $this->roleRepository->findForTenant($id, app('tenant')->id);

        abort_unless($role, 404);

        $this->authorize('update', $role);

        $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $names = array_map('strtolower', \App\Models\User::RESERVED_ROLE_NAMES);
                    if (in_array(strtolower(trim((string) $value)), $names, true)) {
                        $fail(__('The role name is reserved and cannot be used.'));
                    }
                },
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('tenant_id', app('tenant')->id)
                        ->orWhereNull('tenant_id');
                })->ignore($id),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($this->safePermissions($request->permissions));
        }

        $this->roleRepository->clearCache(app('tenant')->id);

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_071'));
    }

    public function destroy($id)
    {
        $role = $this->roleRepository->findForTenant($id, app('tenant')->id);

        abort_unless($role, 404);

        $this->authorize('delete', $role);

        if ($role->users()->count() > 0) {
            return back()->with('error', __('center::messages.msg_072'));
        }

        $role->delete();

        $this->roleRepository->clearCache(app('tenant')->id);

        return redirect()->route('center.roles.index')->with('success', __('center::messages.msg_073'));
    }

    /**
     * Restrict assignable permissions to the center-scope.
     * System-wide groups (e.g. admin.*, centers.*) are reserved for the super-admin portal.
     */
    protected function safePermissions(array $permissions): array
    {
        return array_values(array_filter($permissions, function (string $permission) {
            $parts = explode(' ', $permission);

            return ! in_array($parts[1] ?? '', PermissionService::SYSTEM_GROUPS, true);
        }));
    }
}
