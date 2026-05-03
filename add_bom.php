<?php
$file = __DIR__ . '/public/sample-students.csv';
$content = file_get_contents($file);
if (substr($content, 0, 3) !== "\xEF\xBB\xBF") {
    file_put_contents($file, "\xEF\xBB\xBF" . $content);
    echo "BOM added.\n";
} else {
    echo "BOM already exists.\n";
}
