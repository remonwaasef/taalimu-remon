<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Services\MarketplaceService;

class MarketplaceController extends Controller
{
    public function __construct(protected MarketplaceService $marketplaceService) {}

    public function index()
    {
        $filters = request()->only(['subject', 'search', 'page']);
        $listings = $this->marketplaceService->getActiveListings($filters);

        return view('growth.discovery.listings', compact('listings', 'filters'));
    }

    public function create()
    {
        return view('growth.dashboard.marketplace-create');
    }

    public function store()
    {
        $data = request()->validate([
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'preferred_schedule' => 'nullable|string|max:255',
        ]);

        $tenant = app('tenant');

        $this->marketplaceService->createListing([
            'user_id' => auth()->id(),
            ...$data,
        ], $tenant->id);

        return redirect()->route('growth.dashboard')
            ->with('success', __('Listing created successfully.'));
    }

    public function close(int $id)
    {
        $listing = $this->marketplaceService->getListing($id);

        if (! $listing || $listing->user_id !== auth()->id()) {
            abort(403);
        }

        $this->marketplaceService->closeListing($listing);

        return redirect()->back()
            ->with('success', __('Listing closed.'));
    }
}
