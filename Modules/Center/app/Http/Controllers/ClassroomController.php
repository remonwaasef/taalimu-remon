<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Classroom::class);
        $classrooms = Classroom::withCount('assets')->latest()->paginate(10);
        return view('center::classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Classroom::class);
        return view('center::classrooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Classroom::class);
        if (!app('tenant')->hasFeature('max_classrooms')) {
            return redirect()->back()->with('error', 'لقد وصلت للحد الأقصى من القاعات المسموح به في باقتك.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|in:hall,lab,virtual',
            'color' => 'nullable|string|max:7',
        ]);

        $classroom = Classroom::create($validated);

        // Handle Quick Assets
        if ($request->has('quick_assets')) {
            foreach ($request->quick_assets as $assetName) {
                $classroom->assets()->create([
                    'tenant_id' => app('tenant')->id,
                    'name' => $assetName,
                    'type' => Str::contains($assetName, ['Projector', 'TV', 'شاشة']) ? 'electronics' : (Str::contains($assetName, 'سبورة') ? 'furniture' : 'equipment'),
                    'status' => 'active',
                ]);
            }
        }

        return redirect()->route('center.classrooms.index')
            ->with('success', 'تم إضافة القاعة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $this->authorize('view', $classroom);
        
        $classroom->load(['assets', 'schedules' => function($query) {
            $query->with(['course', 'instructor'])->orderBy('day_of_week')->orderBy('start_time');
        }]);

        return view('center::classrooms.show', compact('classroom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        $this->authorize('update', $classroom);
        return view('center::classrooms.edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorize('update', $classroom);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|in:hall,lab,virtual',
            'color' => 'nullable|string|max:7',
        ]);

        $classroom->update($validated);

        return redirect()->route('center.classrooms.index')
            ->with('success', 'تم تحديث القاعة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        $this->authorize('delete', $classroom);
        $classroom->delete();

        return redirect()->route('center.classrooms.index')
            ->with('success', 'تم حذف القاعة بنجاح');
    }
}
