<?php
$file = 'Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// Find the reset form and replace the route
// The form is the second form in the email templates tab that has "إعادة الضبط للافتراضي"
$search = "route('center.settings.update', ['tenant' => \$tenant->domain ?? 'center'])";
$replace = "route('center.settings.reset-email-templates', ['tenant' => \$tenant->domain ?? 'center'])";

// We need to be specific - only replace the one near "إعادة الضبط"
$pos = strpos($content, 'إعادة الضبط للافتراضي');
if ($pos === false) {
    echo "Could not find reset button text\n";
    exit(1);
}

// Find the form action before this button (search backwards)
$searchBack = "center.settings.update";
$lastPos = strrpos(substr($content, 0, $pos), $searchBack);
if ($lastPos === false) {
    echo "Could not find form action before reset button\n";
    exit(1);
}

// Replace just this occurrence
$content = substr_replace($content, 'center.settings.reset-email-templates', $lastPos, strlen($searchBack));

file_put_contents($file, $content);
echo "Done! Fixed reset button route.\n";
