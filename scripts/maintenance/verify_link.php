<?php

$link = __DIR__.'/public/storage';
if (is_link($link)) {
    echo "Symlink Exists: $link\n";
    echo 'Points to: '.readlink($link)."\n";
} else {
    echo "NOT A SYMLINK: $link\n";
    if (is_dir($link)) {
        echo "It is a DIRECTORY.\n";
    }
}

$target = __DIR__.'/storage/app/public';
echo "Expected Target: $target\n";
if (file_exists($target)) {
    echo "Target directory exists.\n";
    $files = scandir($target.'/bug-reports');
    echo 'Files in bug-reports: '.count($files)."\n";
    print_r(array_slice($files, 0, 10));
} else {
    echo "Target directory DOES NOT EXIST.\n";
}
