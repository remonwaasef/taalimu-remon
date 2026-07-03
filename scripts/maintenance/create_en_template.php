<?php

$file = __DIR__.'/public/sample-students.csv';

$headers = ['name', 'email', 'phone', 'grade_level'];
$data = [
    ['Ahmed Ali', 'ahmed1@example.com', '01012345678', '1'],
    ['Sara Khaled', 'sara2@example.com', '01023456789', '2'],
    ['Mohamed Omar', 'mohamed3@example.com', '01034567890', '3'],
    ['Fatima Saeed', 'fatima4@example.com', '01045678901', '4'],
    ['John Doe', 'john5@example.com', '01056789012', '5'],
];

$handle = fopen($file, 'w');

fputcsv($handle, $headers);
foreach ($data as $row) {
    fputcsv($handle, $row);
}

fclose($handle);

echo "Template created successfully with English data.\n";
