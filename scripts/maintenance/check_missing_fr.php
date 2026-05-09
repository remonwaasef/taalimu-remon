<?php
// Compare AR vs FR translation keys
$files = ['messages', 'students', 'settings', 'dashboard', 'sidebar', 'schedules', 'courses', 'sales', 'analytics', 'academic'];
$basePath = __DIR__ . '/Modules/Center/resources/lang/';

foreach ($files as $file) {
    $arFile = $basePath . 'ar/' . $file . '.php';
    $frFile = $basePath . 'fr/' . $file . '.php';
    
    if (!file_exists($arFile)) { echo "AR $file.php NOT FOUND\n"; continue; }
    if (!file_exists($frFile)) { echo "FR $file.php NOT FOUND\n"; continue; }
    
    $ar = include $arFile;
    $fr = include $frFile;
    
    // Flatten arrays for comparison
    $arFlat = [];
    $frFlat = [];
    
    $flatten = function($arr, $prefix = '') use (&$flatten, &$result) {
        foreach ($arr as $k => $v) {
            $key = $prefix ? $prefix . '.' . $k : $k;
            if (is_array($v)) {
                $flatten($v, $key);
            } else {
                $result[$key] = $v;
            }
        }
    };
    
    $result = [];
    $flatten($ar);
    $arFlat = $result;
    
    $result = [];
    $flatten($fr);
    $frFlat = $result;
    
    $missing = array_diff_key($arFlat, $frFlat);
    $count = count($missing);
    
    if ($count > 0) {
        echo "=== $file.php: $count missing keys ===\n";
        foreach ($missing as $k => $v) {
            echo "  - $k\n";
        }
        echo "\n";
    } else {
        echo "✓ $file.php: OK (all keys present)\n";
    }
}
