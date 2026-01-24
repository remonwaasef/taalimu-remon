<?php
/**
 * Laravel Cache Clearer & Config Checker
 * Place this file in your public folder and access it via yourdomain.com/edu/public/clear_cache.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Laravel Maintenance Tool</h1>";

function runArtisan($app, $command) {
    echo "<li>Running: <code>php artisan $command</code> ... ";
    try {
        Illuminate\Support\Facades\Artisan::call($command);
        echo "<span style='color: green;'>DONE</span></li>";
    } catch (Exception $e) {
        echo "<span style='color: red;'>ERROR: " . $e->getMessage() . "</span></li>";
    }
}

echo "<h2>1. Clearing Caches</h2><ul>";
runArtisan($app, 'config:clear');
runArtisan($app, 'route:clear');
runArtisan($app, 'view:clear');
runArtisan($app, 'cache:clear');
echo "</ul>";

echo "<h2>2. Current Live Configuration</h2><ul>";
echo "<li><strong>APP_ENV:</strong> " . config('app.env') . "</li>";
echo "<li><strong>APP_URL:</strong> " . config('app.url') . "</li>";
echo "<li><strong>TENANT_DOMAIN:</strong> " . config('app.tenant_domain') . "</li>";
echo "<li><strong>DB_HOST:</strong> " . config('database.connections.mysql.host') . "</li>";
echo "<li><strong>DB_DATABASE:</strong> " . config('database.connections.mysql.database') . "</li>";
echo "</ul>";

echo "<h2>3. File Check</h2><ul>";
$configCache = __DIR__ . '/../bootstrap/cache/config.php';
echo "<li><strong>Config Cache File exists:</strong> " . (file_exists($configCache) ? "<span style='color:red;'>YES (Delete this manually if clear:config failed)</span>" : "<span style='color:green;'>NO (Good)</span>") . "</li>";
echo "</ul>";

echo "<p><a href='index.php'>Go to Home Page</a></p>";
