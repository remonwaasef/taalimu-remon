<?php

use App\Models\Enrollment;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$enrollments = Enrollment::with('course')->get();
$fixed = 0;

foreach ($enrollments as $enrollment) {
    if ($enrollment->course && $enrollment->course->sessions_count > 0) {
        $total = $enrollment->course->sessions_count;
        $remaining = $enrollment->remaining_sessions;
        $consumed = $total - $remaining;
        
        $newProgress = min(100, round(($consumed / $total) * 100));
        
        if ($enrollment->progress != $newProgress) {
            $enrollment->update(['progress' => $newProgress]);
            $fixed++;
            echo "Updated enrollment ID {$enrollment->id} Progress to {$newProgress}% (Course: {$enrollment->course->title})\n";
        }
    }
}

echo "Done! Total fixed enrollments: $fixed\n";
