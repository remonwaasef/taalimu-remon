<?php

$dirCenter = __DIR__.'/Modules/Center/resources/views';

// Load all extracted mappings
$testJson = __DIR__.'/extracted_test.json';
$allJson = __DIR__.'/extracted_all_blades.json';

$mappings = [];
if (file_exists($testJson)) {
    $data = json_decode(file_get_contents($testJson), true);
    if (isset($data['center'])) {
        $mappings = array_merge($mappings, $data['center']);
    }
}
if (file_exists($allJson)) {
    $data = json_decode(file_get_contents($allJson), true);
    if (isset($data['center'])) {
        $mappings = array_merge($mappings, $data['center']);
    }
}

if (empty($mappings)) {
    exit('No mappings found to revert.');
}

echo 'Reverting Blade files using '.count($mappings)." keys...\n";

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirCenter));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $originalContent = $content;

        // Pattern 1: {{ __('center::messages.blade_XXXX') }}
        $content = preg_replace_callback('/\{\{\s*__\(\'center::messages\.(blade_\d+)\'\)\s*\}\}/', function ($matches) use ($mappings) {
            $key = $matches[1];

            return $mappings[$key] ?? $matches[0];
        }, $content);

        // Pattern 2: {{ __("center::messages.blade_XXXX") }}
        $content = preg_replace_callback('/\{\{\s*__\("center::messages\.(blade_\d+)"\)\s*\}\}/', function ($matches) use ($mappings) {
            $key = $matches[1];

            return $mappings[$key] ?? $matches[0];
        }, $content);

        // Pattern 3: placeholder="{{ __('center::messages.blade_XXXX') }}"
        // (This is covered by pattern 1 if we are careful, but sometimes quotes vary)

        // Pattern 4: __('center::messages.blade_XXXX') (inside code blocks)
        $content = preg_replace_callback('/__\(\'center::messages\.(blade_\d+)\'\)/', function ($matches) use ($mappings) {
            $key = $matches[1];

            return isset($mappings[$key]) ? "'".$mappings[$key]."'" : $matches[0];
        }, $content);

        if ($content !== $originalContent) {
            file_put_contents($path, $content);
            echo "Reverted: $path\n";
        }
    }
}

echo "Revert complete. Files are now back to Arabic for a fresh extraction.\n";
