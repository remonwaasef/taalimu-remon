<?php

$dirCenter = __DIR__ . '/Modules/Center/resources/views';

$extracted = [];
$counter = 100; // start from 100 to avoid conflicts with test if any

function processBladeDir($dir, $prefix) {
    global $extracted, $counter;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    $patterns = [
        // 1. Text between HTML tags: >العربية<
        '/>\s*(?P<text>[\s،؟!.:\-0-9]*\p{Arabic}[\p{Arabic}\s،؟!.:\-0-9]*)\s*</u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return ">{{ __('{$langGroup}{$key}') }}<";
        },
        // 2. Placeholder attributes: placeholder="العربية"
        '/placeholder="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'placeholder="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 3. title attributes: title="العربية"
        '/title="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'title="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 4. alt attributes: alt="العربية"
        '/alt="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'alt="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 5. value attributes (for submit buttons usually): value="العربية"
        '/value="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'value="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 6. confirm dialogs: confirm('العربية')
        '/confirm\(\'(?P<text>[\p{Arabic}\s،؟!.:\-]+)\'\)/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'confirm(\'{{ __(\'' . $langGroup . $key . '\') }}\')';
        },
        // 7. Simple Blade string literals containing Arabic: 'العربية'
        "/'(?P<text>[\p{Arabic}\s،؟!.:\-]+)'/u" => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (!preg_match('/\p{Arabic}/u', $text)) return $match[0];
            
            // Check if it's already inside a translation function
            // We can't easily check context here securely, but we can assume mostly it's not.
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            
            return "__('{$langGroup}{$key}')";
        }
    ];

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $modified = false;

            foreach ($patterns as $pattern => $callback) {
                $content = preg_replace_callback($pattern, function($matches) use ($callback, &$extracted, &$counter, &$modified, $prefix) {
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

file_put_contents(__DIR__ . '/extracted_all_blades.json', json_encode($extracted, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Extraction complete! Found " . count($extracted['center']) . " strings.\n";
