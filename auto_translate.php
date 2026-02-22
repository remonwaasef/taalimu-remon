<?php

$jsonFile = __DIR__ . '/extracted_all_blades.json';
if (!file_exists($jsonFile)) {
    die("JSON file not found.");
}

$data = json_decode(file_get_contents($jsonFile), true);
$strings = $data['center'] ?? [];

if (empty($strings)) {
    die("No strings to translate.");
}

function translateText($text, $targetLang) {
    // Avoid translating numbers and symbols alone
    if (empty(trim($text)) || preg_match('/^[\s\-\.:0-9]+$/', $text)) {
        return $text;
    }
    
    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=ar&tl={$targetLang}&dt=t&q=" . urlencode($text);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $result = json_decode($response, true);
        if (isset($result[0][0][0])) {
            $translated = '';
            foreach($result[0] as $part) {
                $translated .= $part[0];
            }
            return trim($translated);
        }
    }
    return $text;
}

$ar = [];
$en = [];
$fr = [];

echo "Starting translation for " . count($strings) . " strings...\n";

$count = 0;
foreach ($strings as $key => $arText) {
    if (in_array(trim($arText), ['-', ':'])) {
         $ar[$key] = $arText;
         $en[$key] = $arText;
         $fr[$key] = $arText;
         continue;
    }

    $ar[$key] = $arText;
    
    // Quick delay to avoid rate limiting
    usleep(50000); // 50ms
    
    $en[$key] = translateText($arText, 'en');
    $fr[$key] = translateText($arText, 'fr');
    
    $count++;
    if ($count % 50 == 0) {
        echo "Translated $count / " . count($strings) . "\n";
    }
}

// Ensure the lang directories exist
$baseDir = __DIR__ . '/Modules/Center/resources/lang';
if (!is_dir("$baseDir/ar")) mkdir("$baseDir/ar", 0777, true);
if (!is_dir("$baseDir/en")) mkdir("$baseDir/en", 0777, true);
if (!is_dir("$baseDir/fr")) mkdir("$baseDir/fr", 0777, true);

function appendToLangFile($filePath, $translations) {
    $existing = [];
    if (file_exists($filePath)) {
        $existing = include $filePath;
        if (!is_array($existing)) $existing = [];
    }
    
    $merged = array_merge($existing, $translations);
    
    $content = "<?php\n\nreturn [\n";
    foreach ($merged as $k => $v) {
        $escapedValue = addslashes($v);
        $content .= "    '{$k}' => '{$escapedValue}',\n";
    }
    $content .= "];\n";
    
    file_put_contents($filePath, $content);
}

appendToLangFile("$baseDir/ar/messages.php", $ar);
appendToLangFile("$baseDir/en/messages.php", $en);
appendToLangFile("$baseDir/fr/messages.php", $fr);

echo "Translation complete. Files updated in Modules/Center/resources/lang.\n";
