<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $schedule = Schedule::findOrFail($request->schedule_id);
        $student = Student::findOrFail($request->student_id); // Fetch to trigger TenantScope
        
        // Check if student is already booked for this session
        $exists = Booking::where('student_id', $request->student_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('status', 'confirmed')
            ->exists();

        if ($exists) {
            return back()->with('error', 'الطالب مسجل بالفعل في هذه الحصة.');
        }

        // Check Capacity
        $currentBookingsCount = Booking::where('schedule_id', $request->schedule_id)
            ->where('status', 'confirmed')
            ->count();

        if ($currentBookingsCount >= $schedule->max_students) {
            return back()->with('error', 'عذراً، هذه الحصة مكتملة العدد.');
        }

        Booking::create([
            'tenant_id' => app('tenant')->id,
            'student_id' => $request->student_id,
            'schedule_id' => $request->schedule_id,
            'status' => 'confirmed',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'تم حجز الحصة للطالب بنجاح.');
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('update', $booking);

        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الحجز بنجاح.');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('delete', $booking);
        $booking->delete();

        return back()->with('success', 'تم حذف الحجز بنجاح.');
    }
}
