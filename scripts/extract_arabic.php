<?php

$dir = __DIR__.'/../app/Services';
$langFileAr = __DIR__.'/../resources/lang/ar/services.php';
$langFileEn = __DIR__.'/../resources/lang/en/services.php';
$langFileFr = __DIR__.'/../resources/lang/fr/services.php';

// Ensure lang directories exist
foreach (['ar', 'en', 'fr'] as $locale) {
    if (! is_dir(__DIR__."/../resources/lang/$locale")) {
        mkdir(__DIR__."/../resources/lang/$locale", 0777, true);
    }
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$translations = [];
$counter = 1;

foreach ($phpFiles as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $originalContent = $content;

    // Match strings in single or double quotes containing Arabic letters
    // This regex is slightly simplified but effective for common hardcoded strings
    // We capture the quote type ($m[1]) and the string content ($m[2])
    $pattern = '/(["\'])(.*?(?:[\x{0600}-\x{06FF}]+).*?)\1/u';

    $content = preg_replace_callback($pattern, function ($matches) use (&$translations, &$counter) {
        $quote = $matches[1];
        $text = $matches[2];

        // Skip if it contains complex HTML tags or complex concatenations that might break
        if (strpos($text, '<b') !== false || strpos($text, '<code') !== false) {
            return $matches[0]; // Leave it alone for now
        }

        // Handle {$variable} or $variable inside double quotes
        $placeholders = [];
        if ($quote === '"') {
            $text = preg_replace_callback('/\{\$([a-zA-Z0-9_>-]+)\}|\$([a-zA-Z0-9_>-]+)/', function ($m) use (&$placeholders) {
                $var = ! empty($m[1]) ? $m[1] : $m[2];
                $cleanVar = str_replace('->', '_', $var);
                $placeholders[$cleanVar] = '$'.$var;

                return ':'.$cleanVar;
            }, $text);
        }

        $key = 'string_'.$counter++;
        $translations[$key] = $text;

        if (empty($placeholders)) {
            return "__('services.{$key}')";
        } else {
            $arrayStr = '[';
            foreach ($placeholders as $k => $v) {
                $arrayStr .= "'{$k}' => {$v}, ";
            }
            $arrayStr = rtrim($arrayStr, ', ').']';

            return "__('services.{$key}', {$arrayStr})";
        }

    }, $content);

    if ($content !== $originalContent) {
        file_put_contents($path, $content);
        echo 'Updated: '.basename($path)."\n";
    }
}

// Save translations
$export = "<?php\n\nreturn [\n";
foreach ($translations as $key => $val) {
    // Escape single quotes for the translation file
    $val = str_replace("'", "\'", $val);
    $export .= "    '{$key}' => '{$val}',\n";
}
$export .= "];\n";

file_put_contents($langFileAr, $export);

// Auto-generate empty ones for EN and FR
$exportEn = "<?php\n\nreturn [\n";
foreach ($translations as $key => $val) {
    $exportEn .= "    '{$key}' => '', // TODO: Translate from Arabic: {$val}\n";
}
$exportEn .= "];\n";
file_put_contents($langFileEn, $exportEn);
file_put_contents($langFileFr, $exportEn);

echo 'Extracted '.count($translations)." strings.\n";
