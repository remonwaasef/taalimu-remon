<?php

namespace Modules\Center\Http\Controllers;

use Modules\Center\Http\Controllers\CenterBaseController as Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
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
            ->filter(function($role) {
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        // Get valid role names from Repository
        $validRoles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->pluck('name')
            ->toArray();
                
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => [
                'nullable', 
                'string', 
                'confirmed',
            ],
            'role' => ['nullable', Rule::in($validRoles)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : Hash::make(\Illuminate\Support\Str::random(12)),
        ]);
        
        $user->role = $validated['role'] ?? 'staff'; 
        $user->tenant_id = $this->tenant->id;
        $user->save();

        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        }

        if (isset($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        return redirect()->route('center.users.index', ['tenant' => $this->tenant->domain])
                         ->with('success', __('User created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('update', $user);
        
        $roles = $this->roleRepository->getAllForTenant($this->tenant->id)
            ->filter(function($role) {
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
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
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
        
        if (!empty($validated['role']) && $user->role !== $validated['role']) {
            $user->role = $validated['role'];
            $user->syncRoles([$validated['role']]);
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('center.users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('delete', $user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', __('You cannot delete your own account.'));
        }

        $user->delete();

        return redirect()->route('center.users.index')
            ->with('success', __('User deleted successfully.'));
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => [
                'nullable', 
                'string', 
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', __('center::messages.msg_078'));
    }
}
