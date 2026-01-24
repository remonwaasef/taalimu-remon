<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Show the application landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cache packages for 1 hour as they don't change often
        $packages = Cache::remember('landing_packages', 3600, function () {
            return Package::with('features')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        // Cache features by category for 1 hour
        $featuresByCategory = Cache::remember('landing_features', 3600, function () {
            return Feature::where('is_visible', true)
                ->orderBy('sort_order')
                ->get()
                ->groupBy('category');
        });


        return view('landing.new', compact('packages', 'featuresByCategory'));
    }
}
