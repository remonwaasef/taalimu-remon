<?php

$dirs = [
    'core' => __DIR__.'/app/Http/Controllers',
    'center' => __DIR__.'/Modules/Center/app/Http/Controllers',
];

$extracted = [];
$counter = 1;

function processDir($dir, $prefix)
{
    global $extracted, $counter;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $modified = false;

            // Match ->with('success|error|info|warning', 'Some Arabic or English text')
            // This regex handles single quotes only for simplicity and safety
            $pattern = "/->with\(\s*'([^']+)'\s*,\s*'([^']+)'\s*\)/u";

            $content = preg_replace_callback($pattern, function ($matches) use (&$extracted, &$counter, &$modified, $prefix) {
                $type = $matches[1];
                $text = $matches[2];

                // If it already contains a translation function or is a variable, skip
                if (strpos($text, '__(') !== false || preg_match('/^[a-zA-Z_\.]+$/', $text)) {
                    return $matches[0];
                }

                $key = 'msg_'.sprintf('%03d', $counter++);
                $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';

                $extracted[$prefix][$key] = $text;
                $modified = true;

                return "->with('{$type}', __('{$langGroup}{$key}'))";
            }, $content);

            if ($modified) {
                file_put_contents($path, $content);
                echo "Updated: $path\n";
            }
        }
    }
}

foreach ($dirs as $prefix => $dir) {
    if (is_dir($dir)) {
        processDir($dir, $prefix);
    }
}

file_put_contents(__DIR__.'/extracted_messages.json', json_encode($extracted, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Extraction complete!\n";
