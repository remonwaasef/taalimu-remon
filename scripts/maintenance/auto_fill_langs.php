<?php

function beautifyKey($key)
{
    // Convert discounts_granted to Discounts Granted
    $text = str_replace('_', ' ', $key);
    $text = ucwords($text);

    return $text;
}

$module = 'Center';
$viewsDir = "d:/new project/antigravty/edu/edu/Modules/$module/resources/views";
$langDir = "d:/new project/antigravty/edu/edu/Modules/$module/resources/lang";

$locales = ['ar', 'fr', 'en'];

// 1. Find all translation keys in views
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$foundKeys = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/(?:__|trans|@lang)\s*\(\s*[\'"]center::([a-zA-Z0-9_\-\.]+)/', $content, $matches);
        if (! empty($matches[1])) {
            foreach ($matches[1] as $key) {
                // Sanitize key (remove empty or trailing dots)
                $key = trim($key, '.');
                if (! empty($key)) {
                    $foundKeys[] = $key;
                }
            }
        }
    }
}

$foundKeys = array_unique($foundKeys);
echo 'Found '.count($foundKeys)." unique keys.\n";

// 2. Load all current translations into a cache to avoid repeated file writes
$translationsCache = [];
foreach ($locales as $locale) {
    if (! is_dir("$langDir/$locale")) {
        continue;
    }
    $files = scandir("$langDir/$locale");
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $translationsCache[$locale][$fileName] = include "$langDir/$locale/$file";
        }
    }
}

// 3. Identify missing keys and add them to cache
$addedCount = 0;
foreach ($locales as $locale) {
    foreach ($foundKeys as $fullKey) {
        $parts = explode('.', $fullKey);
        $fileName = $parts[0];
        $keyPath = array_slice($parts, 1);

        if (empty($keyPath)) {
            continue;
        } // Skip keys like center::analytics (no key)

        if (! isset($translationsCache[$locale][$fileName])) {
            $translationsCache[$locale][$fileName] = [];
        }

        $current = &$translationsCache[$locale][$fileName];
        $isMissing = false;
        foreach ($keyPath as $segment) {
            if (! isset($current[$segment])) {
                $current[$segment] = [];
                $isMissing = true;
            }
            $current = &$current[$segment];
        }

        // If it's an empty array, it means it's a leaf node that was missing
        if ($isMissing || empty($current)) {
            $lastSegment = end($keyPath);
            $current = beautifyKey($lastSegment);
            $addedCount++;
        }
    }
}

echo "Identified $addedCount missing translation instances across all locales.\n";

// 4. Save the cache back to files
foreach ($translationsCache as $locale => $files) {
    foreach ($files as $fileName => $data) {
        $filePath = "$langDir/$locale/$fileName.php";

        $export = var_export($data, true);
        // Convert array() to []
        $export = preg_replace('/array \(/', '[', $export);
        $export = preg_replace('/\)/', ']', $export);
        $export = preg_replace('/=> \n\s+\[/', '=> [', $export);

        // Final cleanup for parentheses in strings (previous bug fix)
        $export = preg_replace_callback("/'([^'\\\\]|\\\\.)*'/", function ($m) {
            return str_replace(']', ')', $m[0]);
        }, $export);

        file_put_contents($filePath, "<?php\n\nreturn ".$export.";\n");
    }
}

echo "All missing translations have been auto-filled with beautified keys!\n";
