<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Show the application landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // SEO: Allow manual locale override via URL parameter and persist to session
        if ($request->has('hl') && in_array($request->hl, ['en', 'ar', 'fr'])) {
            session(['locale' => $request->hl]);
            app()->setLocale($request->hl);
            if (auth()->check()) {
                auth()->user()->update(['locale' => $request->hl]);
            }
        }
        // Fetch packages directly (No Cache) to ensure real-time price updates
        $packages = Package::with('features')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Fetch features directly (No Cache)
        $featuresByCategory = Feature::where('is_visible', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('landing.new', compact('packages', 'featuresByCategory'));
    }
}
