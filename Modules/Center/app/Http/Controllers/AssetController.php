<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Asset::class);
        $assets = Asset::with('classroom')
            ->orderBy(\DB::raw('ISNULL(classroom_id)'), 'asc')
            ->orderBy('classroom_id')
            ->latest()
            ->get()
            ->groupBy(function ($asset) {
                return $asset->classroom ? $asset->classroom->name : '---';
            });

        return view('center::assets.index', compact('assets'));
    }

    public function create()
    {
        $this->authorize('create', Asset::class);
        $classrooms = Classroom::select('id', 'name')->get();

        return view('center::assets.create', compact('classrooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Asset::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'type' => 'required|string|in:equipment,furniture,electronics,other',
            'status' => 'required|string|in:active,maintenance,broken,lost',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'purchase_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('center.assets.index')
            ->with('success', __('center::messages.msg_002'));
    }

    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);

        return view('center::assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);
        $classrooms = Classroom::select('id', 'name')->get();

        return view('center::assets.edit', compact('asset', 'classrooms'));
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $this->authorize('update', $asset);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'type' => 'required|string|in:equipment,furniture,electronics,other',
            'status' => 'required|string|in:active,maintenance,broken,lost',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'purchase_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('center.assets.index')
            ->with('success', __('center::messages.msg_003'));
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('delete', $asset);
        $asset->delete();

        return redirect()->route('center.assets.index')
            ->with('success', __('center::messages.msg_004'));
    }
}
