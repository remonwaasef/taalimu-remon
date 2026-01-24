<?php
/**
 * Laravel Deployment Diagnostic Script
 * Place this file in your public_html folder and access it via yourdomain.com/check.php
 */

header('Content-Type: text/html; charset=utf-8');

function check($label, $condition, $message = '') {
    $status = $condition ? '<span style="color: green;">✔ PASS</span>' : '<span style="color: red;">✘ FAIL</span>';
    echo "<li><strong>$label:</strong> $status $message</li>";
}

echo "<h1>Diagnostic Tool</h1>";
echo "<ul>";

// 1. PHP Version
$phpVersion = PHP_VERSION;
check("PHP Version ($phpVersion)", version_compare($phpVersion, '8.2.0', '>='), "Requires 8.2+");

// 2. .env file
$envExists = file_exists(__DIR__ . '/.env') || file_exists(__DIR__ . '/../.env');
$envPath = file_exists(__DIR__ . '/.env') ? __DIR__ . '/.env' : __DIR__ . '/../.env';
check(".env file", $envExists, $envExists ? "Found at $envPath" : "Not found in root or public folder");

if ($envExists) {
    $envContent = file_get_contents($envPath);
    
    // 3. APP_KEY
    check("APP_KEY", strpos($envContent, 'APP_KEY=base64:') !== false, "Ensure APP_KEY is set");
    
    // 4. DB Connection
    try {
        // Simple regex to find DB params
        preg_match('/DB_HOST=(.*)/', $envContent, $matchesHost);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $matchesDb);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $matchesUser);
        preg_match('/DB_PASSWORD=(.*)/', $envContent, $matchesPass);
        
        $host = trim($matchesHost[1] ?? 'localhost');
        $db = trim($matchesDb[1] ?? '');
        $user = trim($matchesUser[1] ?? '');
        $pass = trim($matchesPass[1] ?? '');
        
        if ($db) {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            check("Database Connection", true, "Successfully connected to $db");
        } else {
            check("Database Connection", false, "DB_DATABASE not set in .env");
        }
    } catch (Exception $e) {
        check("Database Connection", false, "Error: " . $e->getMessage());
    }
}

// 5. Permissions
$storagePath = __DIR__ . '/../storage';
if (is_dir($storagePath)) {
    check("Storage writable", is_writable($storagePath), "Folder: $storagePath");
} else {
    check("Storage folder", false, "Not found at $storagePath");
}

$cachePath = __DIR__ . '/../bootstrap/cache';
if (is_dir($cachePath)) {
    check("Cache writable", is_writable($cachePath), "Folder: $cachePath");
}

echo "</ul>";

echo "<h2>Server Info</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
