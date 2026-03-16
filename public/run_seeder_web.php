<?php
/**
 * Seeder Runner for Experimental Data
 * Visit /run_seeder_web.php to execute
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "<h1>Experimental Data Seeder Runner (v2 - Independent Teachers)</h1>";

try {
    echo "Attempting to run ExperimentalDataSeeder...<br>";
    
    // Check if class exists
    if (!class_exists('Database\Seeders\ExperimentalDataSeeder')) {
        echo "Error: ExperimentalDataSeeder class not found. Make sure you saved the file in database/seeders/ExperimentalDataSeeder.php<br>";
        
        $path = __DIR__ . '/../database/seeders/ExperimentalDataSeeder.php';
        if (file_exists($path)) {
            echo "Found file at $path. Loading manually...<br>";
            require_once $path;
        } else {
            die("File not found at $path");
        }
    }

    $exitCode = Artisan::call('db:seed', [
        '--class' => 'ExperimentalDataSeeder',
        '--force' => true
    ]);

    echo "Status code: $exitCode (0 is success)<br>";
    echo "<h2>Artisan Output:</h2>";
    echo "<pre>" . Artisan::output() . "</pre>";

    echo "<h2>Verification:</h2>";
    $teacher1 = DB::table('users')->where('email', 'teacher1@example.com')->first();
    if ($teacher1) {
        $tenant = DB::table('tenants')->where('id', $teacher1->tenant_id)->first();
        echo "✅ teacher1@example.com found (User ID: " . $teacher1->id . ", Role: " . $teacher1->role . ")<br>";
        if ($tenant) {
            echo "✅ Tenant Domain: " . $tenant->domain . " (Type: " . $tenant->type . ", Name: " . $tenant->name . ")<br>";
        }
    } else {
        echo "❌ teacher1@example.com NOT found in database.<br>";
    }

} catch (\Exception $e) {
    echo "<h2>Error Occurred:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
