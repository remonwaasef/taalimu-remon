<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Feature::firstOrCreate(
    ['code' => 'offline_attendance'], 
    ['name' => 'التحضير بدون إنترنت', 'name_en' => 'Offline Attendance', 'type' => 'boolean', 'category' => 'core']
);

\App\Models\Feature::firstOrCreate(
    ['code' => 'smart_search'], 
    ['name' => 'البحث الذكي المتقدم', 'name_en' => 'Smart Search', 'type' => 'boolean', 'category' => 'core']
);

// Optional: Automatically add these features to the highest package (e.g. package ID 3) if you want them enabled by default for testing.
$proPackage = \App\Models\Package::find(3);
if ($proPackage) {
    $proPackage->features()->syncWithoutDetaching([
        \App\Models\Feature::where('code', 'offline_attendance')->first()->id => ['value' => 'true'],
        \App\Models\Feature::where('code', 'smart_search')->first()->id => ['value' => 'true']
    ]);
}

echo "Features seeded successfully!\n";
