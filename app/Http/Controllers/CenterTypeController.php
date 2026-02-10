<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Services\CenterSetupService;
use Illuminate\Support\Facades\Cache;

class CenterTypeController extends Controller
{
    /**
     * Show the center type selection form.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request)
    {
        $token = $request->get('token');
        if (!$token || !Cache::has("setup_token_$token")) {
            return redirect()->route('register');
        }

        return view('auth.center-type-selection', compact('token'));
    }

    /**
     * Store the selected center type and run auto-setup.
     *
     * @param Request $request
     * @param CenterSetupService $setupService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, CenterSetupService $setupService)
    {
        $request->validate([
            'center_type' => 'required|string|in:tutoring,languages,quran,vocational,institute,other',
            'token' => 'required|string',
        ]);

        $tokenKey = "setup_token_" . $request->token;
        $data = Cache::get($tokenKey);
        
        if (!$data) {
            return redirect()->route('register');
        }

        $tenant = Tenant::find($data['tenant_id']);
        if (!$tenant) {
            return redirect()->route('register');
        }

        // Run the auto-setup
        $setupService->setup($tenant, $request->center_type);

        // Clear the token
        Cache::forget($tokenKey);

        // Redirect to registration success page
        return redirect()->route('registration.success');
    }
}
