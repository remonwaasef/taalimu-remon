<?php

$langs = ['ar', 'fr', 'en'];
$files = ['analytics.php', 'settings.php'];

foreach ($langs as $lang) {
    foreach ($files as $file) {
        $path = "d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/$lang/$file";
        if (!file_exists($path)) continue;
        
        $content = file_get_contents($path);
        
        // Fix the broken parentheses in strings
        // Find strings like '... (something]' and change them back to '... (something)'
        // This regex looks for ( followed by any characters that are NOT ) and then a ]
        // BUT it's safer to just replace ']' with ')' if it's NOT at the end of a line or followed by a comma.
        // Actually, the preg_replace replaced ALL closing parentheses.
        
        // Better: Find all strings (enclosed in ') and fix the brackets inside them
        $content = preg_replace_callback("/'([^'\\\\]|\\\\.)*'/", function($m) {
            return str_replace(']', ')', $m[0]);
        }, $content);
        
        file_put_contents($path, $content);
    }
}

echo "Parentheses fixed!";
