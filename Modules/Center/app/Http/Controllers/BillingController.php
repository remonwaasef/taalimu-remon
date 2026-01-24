<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('update', app('tenant'));
        $tenant = app('tenant');
        $subscription = $tenant->activeSubscription();
        $packages = \App\Models\Package::with('features')->get();

        return view('center::billing.index', compact('tenant', 'subscription', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('center::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('center::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('center::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
