<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\Tenant;
use App\Models\User;

$tenant = Tenant::where('domain', 'akadymy-alraaay')->first();
if (! $tenant) {
    echo "Tenant not found\n";
    exit;
}

app()->instance('tenant', $tenant);

$user = User::where('id', 16)->first(); // From logs, user ID 16 was accessing campus.profile
if (! $user) {
    echo "User not found\n";
    exit;
}

$student = $user->student;
if (! $student) {
    echo "Student profile not found for user 16\n";
    exit;
}

echo 'Student ID: '.$student->id."\n";
echo 'Enrolled Courses Count: '.$student->enrollments()->count()."\n";

$enrollments = $student->enrollments()->with('course')->get();
foreach ($enrollments as $enrollment) {
    echo '- Course: '.($enrollment->course->name ?? 'Unknown').' (ID: '.$enrollment->course_id.') Status: '.$enrollment->status."\n";
}

$activeCoursesNotInEnrollment = Course::where('status', 'active')
    ->whereNotIn('id', $enrollments->pluck('course_id'))
    ->get();

echo "\nActive Courses NOT Enrolled: ".$activeCoursesNotInEnrollment->count()."\n";
foreach ($activeCoursesNotInEnrollment as $course) {
    echo '- Course: '.$course->name.' (ID: '.$course->id.")\n";
}
