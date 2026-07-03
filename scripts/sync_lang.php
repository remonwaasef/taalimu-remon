<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$langDirs = [
    'core' => __DIR__.'/resources/lang',
    'center' => __DIR__.'/Modules/Center/resources/lang',
];

$missing = [
    'en' => [],
    'fr' => [],
];

function getKeysRecursive($array, $prefix = '')
{
    $keys = [];
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $keys = array_merge($keys, getKeysRecursive($value, $prefix.$key.'.'));
        } else {
            $keys[$prefix.$key] = $value;
        }
    }

    return $keys;
}

foreach ($langDirs as $type => $baseDir) {
    if (! is_dir($baseDir)) {
        continue;
    }

    $arDir = $baseDir.'/ar';
    if (! is_dir($arDir)) {
        continue;
    }

    $files = glob($arDir.'/*.php');
    foreach ($files as $file) {
        $filename = basename($file);
        $arData = require $file;
        if (! is_array($arData)) {
            continue;
        }

        $arKeys = getKeysRecursive($arData);

        foreach (['en', 'fr'] as $locale) {
            $localeFile = $baseDir.'/'.$locale.'/'.$filename;
            $localeData = file_exists($localeFile) ? require $localeFile : [];
            if (! is_array($localeData)) {
                $localeData = [];
            }

            $localeKeys = getKeysRecursive($localeData);

            foreach ($arKeys as $key => $arValue) {
                if (! array_key_exists($key, $localeKeys) || empty($localeKeys[$key])) {
                    if (! isset($missing[$locale][$type.'/'.$filename])) {
                        $missing[$locale][$type.'/'.$filename] = [];
                    }
                    $missing[$locale][$type.'/'.$filename][$key] = $arValue;
                }
            }
        }
    }
}

file_put_contents(__DIR__.'/missing_translations.json', json_encode($missing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Missing translations written to missing_translations.json\n";
