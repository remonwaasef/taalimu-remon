<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function edit(\App\Models\Course $course)
    {
        $this->authorize('update', $course);
        $course->load(['sections.lessons.quiz', 'sections.lessons.assignment', 'resources']);

        return view('center::curriculum.edit', compact('course'));
    }

    public function storeSection(Request $request, \App\Models\Course $course)
    {
        $this->authorize('update', $course);
        $request->validate(['title' => 'required|string|min:2|max:255']);
        $course->sections()->create([
            'title' => $request->title,
            'sort_order' => $course->sections()->max('sort_order') + 1,
        ]);

        return back()->with('success', __('center::messages.msg_034'));
    }

    public function updateSection(Request $request, \App\Models\Section $section)
    {
        $this->authorize('update', $section->course);
        $request->validate(['title' => 'required|string|min:2|max:255']);
        $section->update(['title' => $request->title]);

        return back()->with('success', __('center::messages.msg_035'));
    }

    public function destroySection(\App\Models\Section $section)
    {
        $this->authorize('update', $section->course);
        $section->delete();

        return back()->with('success', __('center::messages.msg_036'));
    }

    public function storeLesson(Request $request, \App\Models\Section $section)
    {
        $this->authorize('update', $section->course);
        $request->validate(['title' => 'required|string|min:2|max:255']);
        $section->lessons()->create([
            'title' => $request->title,
            'sort_order' => $section->lessons()->max('sort_order') + 1,
        ]);

        return back()->with('success', __('center::messages.msg_037'));
    }

    public function updateLesson(Request $request, \App\Models\Lesson $lesson)
    {
        $this->authorize('update', $lesson->section->course);
        $validated = $request->validate([
            'title' => 'required|string|min:2|max:255',
            'type' => 'required|in:video,text,quiz,assignment',
            'content' => 'nullable|string',
            'duration' => 'nullable|integer',
            'is_free' => 'boolean',
        ]);

        if (isset($validated['type']) && $validated['type'] === 'text' && isset($validated['content'])) {
            // HTMLPurifier (unlike strip_tags) also removes event-handler
            // attributes and javascript: URIs, not just disallowed tags.
            $validated['content'] = \Mews\Purifier\Facades\Purifier::clean($validated['content'], 'lesson');
        }

        $lesson->update($validated);

        return back()->with('success', __('center::messages.msg_038'));
    }

    public function destroyLesson(\App\Models\Lesson $lesson)
    {
        $this->authorize('update', $lesson->section->course);
        $lesson->delete();

        return back()->with('success', __('center::messages.msg_039'));
    }

    public function reorderSections(Request $request, \App\Models\Course $course)
    {
        $this->authorize('update', $course);
        $request->validate(['sections' => 'required|array']);
        foreach ($request->sections as $index => $id) {
            \App\Models\Section::where('id', $id)->where('course_id', $course->id)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'success']);
    }

    public function reorderLessons(Request $request, \App\Models\Section $section)
    {
        $this->authorize('update', $section->course);
        $request->validate(['lessons' => 'required|array']);
        foreach ($request->lessons as $index => $id) {
            \App\Models\Lesson::where('id', $id)->where('section_id', $section->id)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'success']);
    }

    // Unused resource methods removed
}
