<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request, \App\Models\Lesson $lesson)
    {
        $this->authorize('update', $lesson->section->course);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'max_score' => 'required|integer|min:1',
        ]);

        $assignment = $lesson->assignment()->create($validated);

        return redirect()->route('center.assignments.edit', $assignment)->with('success', __('center::messages.msg_005'));
    }

    public function edit(\App\Models\Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        return view('center::assignments.edit', compact('assignment'));
    }

    public function update(Request $request, \App\Models\Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'max_score' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return back()->with('success', __('center::messages.msg_006'));
    }

    // Student Methods
    public function show(\App\Models\Assignment $assignment)
    {
        $this->authorize('view', $assignment);

        $submission = $assignment->submissions()->where('user_id', auth()->id())->first();

        return view('center::assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, \App\Models\Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:10240', // 10MB max
        ]);

        // Store in private storage (storage/app/assignments)
        $path = $request->file('file')->store('assignments/'.$assignment->id);

        $assignment->submissions()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'file_path' => $path,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', __('center::messages.msg_007'));
    }

    public function download(\App\Models\AssignmentSubmission $submission)
    {
        // Use policy for clean, testable authorization
        $this->authorize('download', $submission);

        return \Illuminate\Support\Facades\Storage::download($submission->file_path);
    }

    // Grading Methods
    public function submissions(\App\Models\Assignment $assignment)
    {
        $this->authorize('update', $assignment);
        $submissions = $assignment->submissions()->with('user')->get();

        return view('center::assignments.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, \App\Models\AssignmentSubmission $submission)
    {
        $this->authorize('update', $submission->assignment);
        $request->validate([
            'grade' => 'required|integer|min:0|max:'.$submission->assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', __('center::messages.msg_008'));
    }

    // End of Controller
}
