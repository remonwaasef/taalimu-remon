<?php
$dir = __DIR__ . '/storage/app/public/bug-reports';
if (!file_exists($dir)) {
    if (mkdir($dir, 0777, true)) {
        echo "Created: $dir\n";
    } else {
        echo "Failed to create: $dir\n";
    }
} else {
    echo "Exists: $dir\n";
}
