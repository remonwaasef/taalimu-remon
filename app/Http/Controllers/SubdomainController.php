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
                'message' => app()->getLocale() == 'ar' ? 'النطاق لا يمكن أن يكون فارغاً' : 'Subdomain cannot be empty'
             ]);
        }

        // List of forbidden subdomains from RegistrationController
        $forbidden = ['admin', 'www', 'api', 'app', 'dev', 'test', 'mail', 'webmail', 'portal', 'dashboard', 'edu'];
        
        if (in_array($subdomain, $forbidden)) {
            return response()->json([
                'available' => false,
                'message' => app()->getLocale() == 'ar' ? 'هذا النطاق محجوز للنظام' : 'This subdomain is reserved by the system'
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
                     'message' => app()->getLocale() == 'ar' ? 'هذا النطاق ملكك! يمكنك المتابعة.' : 'This domain is yours! You can proceed.'
                 ]);
             }

            return response()->json([
                'available' => false,
                'message' => app()->getLocale() == 'ar' ? 'هذا النطاق مستخدم بالفعل' : 'This subdomain is already in use'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => app()->getLocale() == 'ar' ? 'النطاق متاح!' : 'Subdomain is available!'
        ]);
    }
}
