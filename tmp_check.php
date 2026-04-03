<?php
// Temporary diagnostic script
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::where('phone', '01192123392')
    ->with(['user', 'enrollments.course', 'sales'])
    ->first();

if (!$student) {
    echo "Student NOT FOUND by phone 01192123392\n";
    // Search more broadly
    $all = App\Models\Student::orderByDesc('id')->limit(5)->get(['id', 'name', 'phone', 'tenant_id']);
    echo "Last 5 students:\n";
    foreach ($all as $s) {
        echo "  ID:{$s->id} Name:{$s->name} Phone:{$s->phone} Tenant:{$s->tenant_id}\n";
    }
} else {
    echo "Found: {$student->name} | ID: {$student->id} | Tenant: {$student->tenant_id}\n";
    echo "Sales count: " . $student->sales->count() . "\n";
    echo "Enrollments count: " . $student->enrollments->count() . "\n";
    foreach ($student->enrollments as $e) {
        echo "  Enrollment -> Course: " . ($e->course->title ?? 'N/A') . " | Course ID: {$e->course_id}\n";
    }
}
