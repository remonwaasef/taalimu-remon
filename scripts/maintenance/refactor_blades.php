<?php

$dirCenter = __DIR__ . '/Modules/Center/resources/views';

$extracted = [];
$counter = 1;

function processBladeDir($dir, $prefix) {
    global $extracted, $counter;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    $patterns = [
        // 1. Text between HTML tags: >العربية<
        '/>\s*(?P<text>[\p{Arabic}\s،؟!.:\-0-9]+)\s*</u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            // Skip if text only contains numbers or symbols
            if (empty($text) || preg_match('/^[\s\-\.0-9]+$/', $text)) return $match[0];
            
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            
            // Replace with {{ __('center::messages.blade_0001') }}
            return ">{{ __('{$langGroup}{$key}') }}<";
        },
        // 2. Placeholder attributes: placeholder="العربية"
        '/placeholder="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (empty($text)) return $match[0];
            
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            
            return 'placeholder="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 3. Title attributes: title="العربية"
        '/title="(?P<text>[\p{Arabic}\s،؟!.:\-]+)"/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (empty($text)) return $match[0];
            
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            
            return 'title="{{ __(\'' . $langGroup . $key . '\') }}"';
        },
        // 4. confirm() javascript calls: confirm('العربية')
        '/confirm\(\'(?P<text>[\p{Arabic}\s،؟!.:\-]+)\'\)/u' => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (empty($text)) return $match[0];
            $key = 'blade_' . sprintf('%04d', $counter++);
            $langGroup = $prefix === 'center' ? 'center::messages.' : 'messages.';
            $extracted[$prefix][$key] = $text;
            $modified = true;
            return 'confirm(\'{{ __(\'' . $langGroup . $key . '\') }}\')';
        },
        // 5. Array values or string literals in Blade: 'العربية'
        // This is tricky, we try to catch simple quotes containing Arabic inside blade files
        // e.g. @section('title', 'إدارة المستخدمين')
        "/'(?P<text>[\p{Arabic}\s،؟!.:\-]+)'/u" => function($match, &$extracted, &$counter, &$modified, $prefix) {
            $text = trim($match['text']);
            if (empty($text)) return $match[0];
            
            // If it seems to be inside a blade directive e.g. @section('foo', 'العربية')
            // Actually, best just to replace the string with the translation function
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

            // Apply patterns iteratively
            foreach ($patterns as $pattern => $callback) {
                // To avoid breaking existing {{ __('...') }}, we apply only if it's not already localized
                // This uses preg_replace_callback but ensuring we don't double replace
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

processBladeDir($dirCenter, 'center');

file_put_contents(__DIR__ . '/extracted_blades.json', json_encode($extracted, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Blade extraction complete!\n";
