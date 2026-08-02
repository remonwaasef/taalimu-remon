<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function flatten(array $arr, string $prefix = ''): array {
    $out = [];
    foreach ($arr as $k => $v) {
        $key = $prefix ? $prefix.'.'.$k : $k;
        if (is_array($v)) {
            $out = array_merge($out, flatten($v, $key));
        } else {
            $out[] = $key;
        }
    }
    return $out;
}

// Collect all used keys
$viewsDir = 'Modules/Admin/resources/views';
$used = [];
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($rii as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        preg_match_all("/__\(\s*'admin::admin\.([^']+)'/", $content, $m1);
        foreach ($m1[1] as $key) { $used[$key] = true; }
        preg_match_all('/__\(\s*"admin::admin\.([^"]+)"/', $content, $m2);
        foreach ($m2[1] as $key) { $used[$key] = true; }
    }
}

foreach (['ar', 'en', 'fr'] as $lang) {
    $file = "Modules/Admin/lang/$lang/admin.php";
    if (! file_exists($file)) { echo "$lang: FILE MISSING\n"; continue; }
    $langKeys = array_flip(flatten(require $file));
    $missing = [];
    foreach (array_keys($used) as $key) {
        if (! isset($langKeys[$key])) $missing[] = $key;
    }
    echo "$lang: missing ".count($missing)." keys\n";
    foreach ($missing as $k) echo "   $k\n";
}
