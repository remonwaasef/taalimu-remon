<?php

$arFiles = glob('Modules/Center/resources/lang/ar/*.php');
foreach ($arFiles as $arFile) {
    $basename = basename($arFile);
    $frFile = 'Modules/Center/resources/lang/fr/'.$basename;
    if (file_exists($frFile)) {
        $ar = require $arFile;
        $fr = require $frFile;
        if (is_array($ar) && is_array($fr)) {
            $missing = array_diff_key($ar, $fr);
            if (! empty($missing)) {
                echo "Missing in $basename:\n";
                print_r(array_keys($missing));
            }
        }
    } else {
        echo "Missing file: $basename in fr/\n";
    }
}
