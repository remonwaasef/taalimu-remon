<?php

$dirCenter = __DIR__.'/Modules/Center/resources/views/instructors';

$extracted = [];
$counter = 1;

function processBladeDir($dir, $prefix)
{
    global $extracted, $counter;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    $patterns = [
        // 1. Text between HTML tags: >العربية<
        '/>\s*(?P<text>[\p{Arabic}\s،؟!.:\-0-9]+)\s*</u' => function ($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            // Must contain at least one Arabic character
            if (! preg_match('/\p{Arabic}/u', $text)) {
                return $match[0];
            }

            $key = 'blade_'.sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;

            return ">{{ __('{$langGroup}{$key}') }}<";
        },
        // 2. Placeholder attributes: placeholder="العربية"
        '/placeholder="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function ($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (! preg_match('/\p{Arabic}/u', $text)) {
                return $match[0];
            }

            $key = 'blade_'.sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;

            return 'placeholder="{{ __(\''.$langGroup.$key.'\') }}"';
        },
        // 3. Simple Blade string literals containing Arabic: 'العربية'
        "/'(?P<text>[\p{Arabic}\s،؟!.:\-]+)'/u" => function ($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (! preg_match('/\p{Arabic}/u', $text)) {
                return $match[0];
            }

            $key = 'blade_'.sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;

            return "__('{$langGroup}{$key}')";
        },
    ];

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $modified = false;

            foreach ($patterns as $pattern => $callback) {
                $content = preg_replace_callback($pattern, function ($matches) use ($callback, &$extracted, &$counter, &$modified, $prefix) {
                    return $callback($matches, $extracted, $counter, $modified, $prefix);
                }, $content);
            }

            if ($modified) {
                file_put_contents($path, $content);
                echo "Updated: $path\n";
            }
        }
    }
}

$extracted['center'] = [];
processBladeDir($dirCenter, 'center');

file_put_contents(__DIR__.'/extracted_test.json', json_encode($extracted, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Testing complete!\n";
