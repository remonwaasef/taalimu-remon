<?php

namespace App\Http\Controllers;

use App\Models\Tenant;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user && $user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);

            if ($tenant) {
                if ($user->role === 'instructor' || $tenant->type === 'instructor') {
                    return redirect()->away(tenant_url('instructor', $tenant));
                }

                return redirect()->away(tenant_url('dashboard', $tenant));
            }
        }

        return redirect()->route('login.portal');
    }
}
