<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Package;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Http\Request;

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

        // Set Dynamic SEO Metadata based on locale & new positioning
        $title = __('landing-v3.seo.title');
        $description = __('landing-v3.seo.description');

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setCanonical(url()->current());

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'website');
        OpenGraph::addImage(asset('images/hero-dashboard.webp'));

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setImage(asset('images/hero-dashboard.webp'));

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::setType('WebApplication');
        JsonLd::addImage(asset('images/brand/logo-full.png'));

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

        return view('landing.v3.layout', compact('packages', 'featuresByCategory'));
    }
}
