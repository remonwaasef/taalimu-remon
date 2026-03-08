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
                'qr_identifier' => Str::random(8),
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
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
            'addQuietzone' => true,
        ]);
        
        $qrCode = (new QRCode($options))->render($user->qr_identifier);
        
        // Convert SVG to Data URL for 100% reliable rendering
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
        
        return view('center::groups.registration_success', compact('user', 'qrCode'));
    }
}
