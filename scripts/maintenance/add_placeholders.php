<?php

function updateLangFile($path, $newKeys) {
    if (!file_exists($path)) return;
    $content = include $path;
    $content = array_replace_recursive($content, $newKeys);
    $export = var_export($content, true);
    $export = preg_replace('/array \(/', '[', $export);
    $export = preg_replace('/\)/', ']', $export);
    $export = preg_replace('/=> \n\s+\[/', '=> [', $export);
    $export = preg_replace_callback("/'([^'\\\\]|\\\\.)*'/", function($m) {
        return str_replace(']', ')', $m[0]);
    }, $export);
    file_put_contents($path, "<?php\n\nreturn " . $export . ";\n");
}

$ar = ['reminders' => ['whatsapp_placeholder' => 'تذكير: مصروفات الطالب/ة {student_name} بمبلغ {amount} مستحقة بتاريخ {due_date}. يرجى السداد. {center_name}']];
$fr = ['reminders' => ['whatsapp_placeholder' => "Rappel : Les frais de l'étudiant {student_name} ({amount}) sont dus le {due_date}. Merci de payer. {center_name}"]];
$en = ['reminders' => ['whatsapp_placeholder' => "Reminder: Student {student_name}'s fees ({amount}) are due on {due_date}. Please pay. {center_name}"]];

updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/ar/settings.php", $ar);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/fr/settings.php", $fr);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/en/settings.php", $en);

echo "Placeholders added!";
