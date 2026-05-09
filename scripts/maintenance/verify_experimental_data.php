<?php

use App\Models\Tenant;
use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Instructor;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$centers = ['exp-smart-center', 'exp-future-academy', 'exp-excellence-hub'];

foreach ($centers as $domain) {
    echo "Checking Center: $domain\n";
    $tenant = Tenant::where('domain', $domain)->first();
    
    if (!$tenant) {
        echo " - Tenant not found!\n";
        continue;
    }
    
    $studentCount = User::where('tenant_id', $tenant->id)->where('role', 'student')->count();
    $instructorCount = User::where('tenant_id', $tenant->id)->where('role', 'instructor')->count();
    $courseCount = Course::where('tenant_id', $tenant->id)->count();
    
    echo " - Students: $studentCount (Expected: 20)\n";
    echo " - Instructors: $instructorCount (Expected: 3)\n";
    echo " - Courses: $courseCount (Expected: 3)\n";
    echo "----------------------------------------\n";
}
