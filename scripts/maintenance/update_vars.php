<?php

$files = [
    'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php',
    'd:/new project/antigravty/edu/edu/app/Console/Commands/SendPaymentRemindersCommand.php',
];

// 1. Update index.blade.php (UI)
$content = file_get_contents($files[0]);

// Replace variable badges in UI
$mappings = [
    '{student_name}' => '{اسم_الطالب}',
    '{center_name}' => '{اسم_المركز}',
    '{amount}' => '{المبلغ}',
    '{due_date}' => '{تاريخ_الاستحقاق}',
    '{remaining}' => '{المبلغ_المتبقي}',
    '{group_name}' => '{اسم_المجموعة}',
    '{course_price}' => '{سعر_الدورة}',
    '{entry_link}' => '{رابط_الدخول}',
    '{password}' => '{كلمة_المرور}',
];

foreach ($mappings as $old => $new) {
    $content = str_replace($old, $new, $content);
}

// Update Presets Data in blade
$content = str_replace(
    "['{اسم_الطالب}', '{اسم_المركز}', '{المبلغ}', '{تاريخ_الاستحقاق}', '{المبلغ_المتبقي}', '{اسم_المجموعة}', '{سعر_الدورة}']",
    "['{اسم_الطالب}', '{اسم_المركز}', '{المبلغ}', '{تاريخ_الاستحقاق}', '{المبلغ_المتبقي}', '{اسم_المجموعة}', '{سعر_الدورة}', '{رابط_الدخول}', '{كلمة_المرور}']",
    $content
);

file_put_contents($files[0], $content);

// 2. Update SendPaymentRemindersCommand.php (Backend)
$content = file_get_contents($files[1]);

// Update sendEmailReminder variables
$oldVars = "            \$variables = [
                'اسم_الطالب' => \$student->name,
                'اسم_المركز' => \$tenant->name,
                'المبلغ' => \$fee,
                'تاريخ_الاستحقاق' => \$dueDay . ' من كل شهر',
                'رابط_الدخول' => url('/login'),
            ];";

$newVars = "            \$variables = [
                'اسم_الطالب' => \$student->name,
                'اسم_المركز' => \$tenant->name,
                'المبلغ' => \$fee,
                'تاريخ_الاستحقاق' => \$dueDay . ' من كل شهر',
                'المبلغ_المتبقي' => \$fee, // Fallback
                'اسم_المجموعة' => 'المجموعة الدراسية', // Generic
                'سعر_الدورة' => \$fee,
                'رابط_الدخول' => url('/login'),
                'كلمة_المرور' => '******',
            ];";

$content = str_replace($oldVars, $newVars, $content);

// Update sendWhatsAppReminder logic to use Arabic placeholders and {format}
$oldWaLogic = "        // Build message
        \$currency = \$tenant->settings['currency'] ?? 'ج.م';
        if (!empty(\$template)) {
            \$message = strtr(\$template, [
                ':student_name' => \$student->name,
                ':amount' => number_format(\$fee, 2),
                ':due_day' => \$dueDay,
                ':tenant_name' => \$tenant->name,
                ':month' => now()->translatedFormat('F'),
                ':currency' => \$currency,
            ]);
        } else {";

$newWaLogic = "        // Build message
        \$currency = \$tenant->settings['currency'] ?? 'ج.م';
        if (!empty(\$template)) {
            \$variables = [
                'اسم_الطالب' => \$student->name,
                'اسم_المركز' => \$tenant->name,
                'المبلغ' => number_format(\$fee, 2) . ' ' . \$currency,
                'تاريخ_الاستحقاق' => \$dueDay . ' من كل شهر',
                'المبلغ_المتبقي' => number_format(\$fee, 2) . ' ' . \$currency,
                'رابط_الدخول' => url('/login'),
            ];
            
            \$message = \$template;
            foreach (\$variables as \$key => \$value) {
                \$message = str_replace('{' . \$key . '}', (string) \$value, \$message);
            }
        } else {";

$content = str_replace($oldWaLogic, $newWaLogic, $content);

file_put_contents($files[1], $content);

echo 'Variables updated successfully!';
