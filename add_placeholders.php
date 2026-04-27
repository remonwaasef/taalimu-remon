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

$ar = ['reminders' => ['whatsapp_placeholder' => 'تذكير: مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}. يرجى السداد. {اسم_المركز}']];
$fr = ['reminders' => ['whatsapp_placeholder' => "Rappel : Les frais de l'étudiant {اسم_الطالب} ({المبلغ}) sont dus le {تاريخ_الاستحقاق}. Merci de payer. {اسم_المركز}"]];
$en = ['reminders' => ['whatsapp_placeholder' => "Reminder: Student {اسم_الطالب}'s fees ({المبلغ}) are due on {تاريخ_الاستحقاق}. Please pay. {اسم_المركز}"]];

updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/ar/settings.php", $ar);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/fr/settings.php", $fr);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/en/settings.php", $en);

echo "Placeholders added!";
