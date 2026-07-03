<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Force fix the course
$course = Course::where('title', 'like', '%عربي%')->first();
if ($course) {
    $course->update(['sessions_count' => 10]);
    echo "STEP 1: Updated course '{$course->title}' to 10 sessions.\n";
} else {
    exit("Course 'عربي' not found.\n");
}

// 2. Force fix the student enrollment
$student = Student::where('code', 'S-3-1001')->first();
if ($student) {
    $enrollment = Enrollment::where('user_id', $student->user_id)
        ->where('course_id', $course->id)
        ->first();

    if ($enrollment) {
        $present = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $enrollment->update(['remaining_sessions' => 10 - $present]);
        echo "STEP 2: Updated student '{$student->name}' enrollment (Remaining: ".(10 - $present).").\n";
    } else {
        echo "Enrollment for student in this course not found.\n";
    }
} else {
    echo "Student not found.\n";
}
