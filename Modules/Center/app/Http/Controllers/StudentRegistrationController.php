<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class StudentRegistrationController extends Controller
{
    public function index($token)
    {
        $course = Course::where('registration_token', $token)->firstOrFail();
        
        return view('center::groups.register', compact('course'));
    }

    public function store(Request $request, $token)
    {
        $course = Course::where('registration_token', $token)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        // check if user already exists by phone
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->phone . '@' . ($course->tenant->domain ?? 'taalimu') . '.com',
                'phone' => $request->phone,
                'password' => Hash::make($request->phone), // Default password is phone
                'role' => 'student',
                'qr_identifier' => Str::random(12),
                'tenant_id' => $course->tenant_id,
            ]);

            $user->assignRole('student');

            Student::create([
                'user_id' => $user->id,
                'tenant_id' => $course->tenant_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'parent_phone' => $request->parent_phone,
                'status' => 'active',
            ]);
        } else {
            // Ensure existing user has a qr_identifier if they are a student
            if ($user->role === 'student' && empty($user->qr_identifier)) {
                $user->update(['qr_identifier' => Str::random(12)]);
            }
        }

        // Enroll in the course if not already enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled) {
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'tenant_id' => $course->tenant_id,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        return redirect()->route('group.registration.success', ['user' => $user->id]);
    }

    public function success(User $user)
    {
        // Use a reliable external QR generator that works across all mobile browsers
        // This avoids issues with raw SVG injection or data URI encoding
        $qrCode = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($user->qr_identifier);
        
        return view('center::groups.registration_success', compact('user', 'qrCode'));
    }
}
