<?php

use App\Models\Student;
use App\Models\Enrollment;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = Student::where('code', 'S-3-1001')->first();

if ($student) {
    foreach ($student->enrollments as $enrollment) {
        $total = $enrollment->course->sessions_count ?? 10;
        $present = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->where('course_id', $enrollment->course_id)
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        $enrollment->update([
            'remaining_sessions' => max(0, $total - $present)
        ]);
        echo "Fixed enrollment for course: " . $enrollment->course->title . " (Total: $total, Present: $present, Remaining: " . ($total - $present) . ")\n";
    }
} else {
    echo "Student not found.\n";
}
