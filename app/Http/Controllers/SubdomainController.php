<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    /**
     * Validate if a subdomain is available.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateSubdomain(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|string|max:255',
        ]);

        $subdomain = strtolower($request->subdomain);
        
        // Clean the subdomain exactly as RegistrationController does
        $subdomain = preg_replace('/[^a-z0-9-]/', '', $subdomain);
        $subdomain = preg_replace('/-+/', '-', $subdomain);
        $subdomain = trim($subdomain, '-');

        if (empty($subdomain)) {
             return response()->json([
                'available' => false,
                'message' => __('messages.subdomain_empty')
             ]);
        }

        // List of forbidden subdomains from RegistrationController
        $forbidden = ['admin', 'www', 'api', 'app', 'dev', 'test', 'mail', 'webmail', 'portal', 'dashboard', 'edu'];
        
        if (in_array($subdomain, $forbidden)) {
            return response()->json([
                'available' => false,
                'message' => __('messages.subdomain_reserved')
            ]);
        }

        // Check against existing tenants
        $tenant = Tenant::where('domain', $subdomain)->first();

        if ($tenant) {
             // SMART RESUME: If current user/google-session email matches the tenant email, say it's available!
             $currentUserEmail = auth()->user()?->email ?? session('google_user.email');
             if ($currentUserEmail && strtolower($tenant->email) === strtolower($currentUserEmail)) {
                 return response()->json([
                     'available' => true,
                     'message' => __('messages.subdomain_yours')
                 ]);
             }

            return response()->json([
                'available' => false,
                'message' => __('messages.subdomain_in_use')
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => __('messages.subdomain_available')
        ]);
    }
}
