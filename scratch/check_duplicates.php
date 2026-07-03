<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sale;
use App\Models\Student;

$studentId = 11;
$student = Student::find($studentId);

if (! $student) {
    exit("Student not found\n");
}

echo "Student: {$student->name} (ID: {$student->id})\n";

$sales = Sale::where('student_id', $student->id)->with('items')->get();

echo 'Total Sales: '.$sales->count()."\n";

$seenCourses = [];
$toDelete = [];

foreach ($sales as $sale) {
    foreach ($sale->items as $item) {
        if ($item->item_type === 'App\Models\Course') {
            $key = $item->item_id;
            if (isset($seenCourses[$key])) {
                echo "Duplicate found: Sale #{$sale->id} for Course #{$key}\n";
                $toDelete[] = $sale->id;
            } else {
                $seenCourses[$key] = $sale->id;
            }
        }
    }
}

echo 'Suggested for deletion: '.implode(', ', $toDelete)."\n";
