<?php

$module = 'Center';
$viewsDir = "d:/new project/antigravty/edu/edu/Modules/$module/resources/views";
$langDir = "d:/new project/antigravty/edu/edu/Modules/$module/resources/lang";

$locales = ['ar', 'fr', 'en'];
$allMissing = [];

// 1. Find all translation keys in views
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$foundKeys = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        // Regex to match center::file.key
        // Matches: __('center::analytics.discounts_granted')
        // Matches: @lang('center::settings.title')
        // Matches: trans('center::sidebar.dashboard')
        preg_match_all('/(?:__|trans|@lang)\s*\(\s*[\'"]center::([a-zA-Z0-9_\-\.]+)/', $content, $matches);

        if (! empty($matches[1])) {
            foreach ($matches[1] as $key) {
                $foundKeys[] = $key;
            }
        }
    }
}

$foundKeys = array_unique($foundKeys);
echo 'Found '.count($foundKeys)." unique keys in Center module views.\n";

// 2. Check each key in lang files
foreach ($locales as $locale) {
    echo "Processing locale: $locale\n";
    foreach ($foundKeys as $fullKey) {
        $parts = explode('.', $fullKey);
        $fileName = $parts[0];
        $keyPath = array_slice($parts, 1);

        $filePath = "$langDir/$locale/$fileName.php";

        if (! file_exists($filePath)) {
            $allMissing[$locale][$fileName][] = [
                'key' => implode('.', $keyPath),
                'full' => $fullKey,
            ];

            continue;
        }

        $translations = include $filePath;

        // Traverse nested array
        $current = $translations;
        $exists = true;
        foreach ($keyPath as $segment) {
            if (isset($current[$segment])) {
                $current = $current[$segment];
            } else {
                $exists = false;
                break;
            }
        }

        if (! $exists) {
            $allMissing[$locale][$fileName][] = [
                'key' => implode('.', $keyPath),
                'full' => $fullKey,
            ];
        }
    }
}

// 3. Report missing keys
if (empty($allMissing)) {
    echo "No missing translations found in Center module!\n";
} else {
    foreach ($allMissing as $locale => $files) {
        echo "\n[$locale] Missing keys:\n";
        foreach ($files as $file => $keys) {
            echo "  $file.php: ".count($keys)." keys missing\n";
            foreach ($keys as $k) {
                echo '    - '.$k['key'].' (Full: '.$k['full'].")\n";
            }
        }
    }
}

// 4. Optionally: Add them with a placeholder
// I will not do it automatically yet, I want to see the list first.
