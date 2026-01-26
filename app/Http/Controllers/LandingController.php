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
