<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BugReport;
use Illuminate\Support\Facades\Storage;

$report = BugReport::latest()->first();

echo "Report ID: #{$report->id}\n";
echo "DB Screenshot Path: {$report->screenshot}\n";

$fullPath = Storage::disk('public')->path($report->screenshot);
echo "Full Disk Path: {$fullPath}\n";

if (file_exists($fullPath)) {
    echo "✅ FILE EXISTS ON DISK!\n";
    echo "Size: " . filesize($fullPath) . " bytes\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
} else {
    echo "❌ FILE DOES NOT EXIST ON DISK!\n";
    // Check parent dir
    $dir = dirname($fullPath);
    if (is_dir($dir)) {
        echo "Parent directory exists: $dir\n";
        echo "Files in parent: " . count(scandir($dir)) . "\n";
    } else {
        echo "Parent directory DOES NOT EXIST: $dir\n";
    }
}

$url = asset('storage/' . $report->screenshot);
echo "Browser URL: $url\n";
