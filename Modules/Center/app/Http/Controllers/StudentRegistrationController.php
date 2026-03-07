<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Models\Guardian;
use App\Services\StudentService;
use App\DTOs\StudentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentRegistrationController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function showRegistrationForm($uuid)
    {
        $classroom = Classroom::where('invite_uuid', $uuid)
            ->where('is_registration_open', true)
            ->firstOrFail();

        $tenant = app('tenant');

        return view('center::groups.register', compact('classroom', 'tenant'));
    }

    public function register(Request $request, $uuid)
    {
        $classroom = Classroom::where('invite_uuid', $uuid)
            ->where('is_registration_open', true)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        // Check if student already exists by phone in this tenant
        $existingUser = User::where('tenant_id', app('tenant')->id)
            ->where('phone', $request->phone)
            ->first();

        if ($existingUser) {
            // Check if already in this group
            $student = $existingUser->student;
            if ($student) {
                // Enrollment logic here if needed
                // For now, let's just say they are registered
                return redirect()->back()->with('info', 'أنت مسجل بالفعل في النظام بهذا الرقم.');
            }
        }

        // Create student data DTO
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'parent_phone' => $request->parent_phone,
            'grade_id' => null, // Tutors might not use grades stringently
            'status' => 'active',
        ];

        // Register student using service
        $result = $this->studentService->registerStudent(StudentData::fromArray($data), $classroom->tenant->admin ?? User::where('tenant_id', $classroom->tenant_id)->where('role', 'center_admin')->first());

        $student = $result['student'];
        $user = $result['user'];

        // Assign to classroom/group
        // Assuming there is a many-to-many or enrollment table
        // Looking at the codebase, it seems to use Enrollments
        \App\Models\Enrollment::create([
            'tenant_id' => app('tenant')->id,
            'student_id' => $student->id,
            'course_id' => null, // Can be null if it's a general group
            'classroom_id' => $classroom->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Generate QR Code Data URI
        $qrCodeDataUri = (new \chillerlan\QRCode\QRCode)->render($user->qr_identifier);

        return view('center::groups.registration_success', [
            'student' => $student,
            'user' => $user,
            'classroom' => $classroom,
            'qr_code_uri' => $qrCodeDataUri
        ]);
    }
}
