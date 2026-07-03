<?php

$file = __DIR__.'/storage/app/public/bug-reports/69ef09c7351c5_auto.png';
if (file_exists($file)) {
    echo "File Exists: $file\n";
    echo 'Permissions: '.substr(sprintf('%o', fileperms($file)), -4)."\n";
    echo 'Owner: '.posix_getpwuid(fileowner($file))['name']."\n";
} else {
    echo "File DOES NOT EXIST in storage/app/public: $file\n";
}

$file2 = __DIR__.'/public/storage/bug-reports/69ef09c7351c5_auto.png';
if (file_exists($file2)) {
    echo "File Exists in public: $file2\n";
    echo 'Permissions: '.substr(sprintf('%o', fileperms($file2)), -4)."\n";
} else {
    echo "File DOES NOT EXIST in public/storage: $file2\n";
}
