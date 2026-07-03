<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Center\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Branch::class);
        $branches = Branch::where('tenant_id', app('tenant')->id)->with('manager')->get();

        return view('center::branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Branch::class);
        // Get potential managers (e.g. users with role center_admin or manager, or just any user)
        $users = User::where('tenant_id', app('tenant')->id)->get();

        return view('center::branches.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Branch::class);
        if (! app('tenant')->hasFeature('max_branches')) {
            return redirect()->back()->with('error', __('center::messages.msg_020'));
        }

        $request->validate([
            'name' => 'nullable|string|max:255',

            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Branch::create([
            'tenant_id' => app('tenant')->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('center.branches.index', ['tenant' => app('tenant')->domain])
            ->with('success', __('Branch created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $branch = Branch::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $branch);
        $users = User::where('tenant_id', app('tenant')->id)->get();

        return view('center::branches.edit', compact('branch', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $branch);

        $request->validate([
            'name' => 'nullable|string|max:255',

            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $branch->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('center.branches.index', ['tenant' => app('tenant')->domain])
            ->with('success', __('Branch updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $branch);

        // Prevent deleting if it has related data?
        // For now, let's allow soft delete as per model trait.
        $branch->delete();

        return redirect()->route('center.branches.index', ['tenant' => app('tenant')->domain])
            ->with('success', __('Branch deleted successfully.'));
    }
}
