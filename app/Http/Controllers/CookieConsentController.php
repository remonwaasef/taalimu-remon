<?php

namespace App\Http\Controllers;

use App\Models\UserConsent;
use Illuminate\Http\Request;

class CookieConsentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'analytics' => 'nullable|boolean',
            'marketing' => 'nullable|boolean',
        ]);

        UserConsent::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'analytics_consent' => (bool) ($validated['analytics'] ?? false),
            'marketing_consent' => (bool) ($validated['marketing'] ?? false),
            'consent_date' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
