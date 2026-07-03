<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,png,jpg,jpeg,gif,mp4,mp3,webm|max:10240', // 10MB limit
            'lesson_id' => 'nullable|exists:lessons,id',
            'is_public' => 'boolean',
        ]);

        $tenantId = app('tenant')->id;
        $path = $request->file('file')->store("{$tenantId}/resources/".$course->id, 'local');

        CourseResource::create([
            'course_id' => $course->id,
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'file_size' => $request->file('file')->getSize(),
            'is_public' => $request->is_public ?? false,
        ]);

        return back()->with('success', __('center::messages.msg_068'));
    }

    public function destroy(CourseResource $resource)
    {
        $this->authorize('update', $resource->course);

        Storage::disk('local')->delete($resource->file_path);
        $resource->delete();

        return back()->with('success', __('center::messages.msg_069'));
    }

    public function download(CourseResource $resource)
    {
        $user = auth()->user();

        // Admin/Instructor can always download
        if ($user->hasAnyRole(['admin', 'center_admin', 'instructor'])) {
            return Storage::disk('local')->download($resource->file_path, $resource->title.'.'.$resource->file_type);
        }

        // Student check
        $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $resource->course_id)
            ->exists();

        if (! $isEnrolled && ! $resource->is_public) {
            abort(403, 'يجب الاشتراك في الدورة للوصول لهذا المورد');
        }

        return Storage::disk('local')->download($resource->file_path, $resource->title.'.'.$resource->file_type);
    }
}
