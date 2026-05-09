<?php
$file = __DIR__ . '/public/sample-students.csv';

$headers = ['name', 'email', 'phone', 'grade_level'];
$data = [
    ['أحمد محمد', 'ahmed1@example.com', '01012345678', '1'],
    ['سارة خالد', 'sara2@example.com', '01023456789', '2'],
    ['محمد علي', 'mohamed3@example.com', '01034567890', '3'],
    ['فاطمة سعيد', 'fatima4@example.com', '01045678901', '4'],
    ['خالد عبد الله', 'khaled5@example.com', '01056789012', '5']
];

$handle = fopen($file, 'w');

// Add BOM
fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($handle, $headers);
foreach ($data as $row) {
    fputcsv($handle, $row);
}

fclose($handle);

echo "Template created successfully with valid UTF-8 BOM.\n";
