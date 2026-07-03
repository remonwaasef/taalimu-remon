<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Store a newly created booking.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Booking::class);
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule = Schedule::where('tenant_id', app('tenant')->id)->findOrFail($request->schedule_id);
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($request->student_id);

        // Check if student is already booked for this session
        $exists = Booking::where('student_id', $request->student_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('status', 'confirmed')
            ->exists();

        if ($exists) {
            return back()->with('error', __('center::messages.msg_015'));
        }

        // Check Capacity
        $currentBookingsCount = Booking::where('schedule_id', $request->schedule_id)
            ->where('status', 'confirmed')
            ->count();

        if ($currentBookingsCount >= $schedule->max_students) {
            return back()->with('error', __('center::messages.msg_016'));
        }

        Booking::create([
            'tenant_id' => app('tenant')->id,
            'student_id' => $request->student_id,
            'schedule_id' => $request->schedule_id,
            'status' => 'confirmed',
            'notes' => $request->notes,
        ]);

        return back()->with('success', __('center::messages.msg_017'));
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $booking = Booking::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $booking);

        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', __('center::messages.msg_018'));
    }

    /**
     * Remove the specified booking.
     */
    public function destroy($id): RedirectResponse
    {
        $booking = Booking::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $booking);
        $booking->delete();

        return back()->with('success', __('center::messages.msg_019'));
    }
}
