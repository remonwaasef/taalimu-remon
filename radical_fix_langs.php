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

$arNew = [
    'reminders' => [
        'timeline_preview' => 'معاينة الجدول الزمني',
        'timeline_desc' => 'شرح توضيحي لمواعيد إرسال التنبيهات تلقائياً.',
    ],
    'email_templates' => [
        'presets' => [
            'formal' => 'ترحيب رسمي',
            'friendly' => 'ترحيب ودي',
            'minimal' => 'إشعار بسيط',
        ]
    ]
];

$frNew = [
    'reminders' => [
        'timeline_preview' => 'Aperçu du Calendrier',
        'timeline_desc' => 'Visualisation des rappels automatiques.',
    ],
    'email_templates' => [
        'presets' => [
            'formal' => 'Accueil Formel',
            'friendly' => 'Accueil Amical',
            'minimal' => 'Notification Simple',
        ]
    ]
];

$enNew = [
    'reminders' => [
        'timeline_preview' => 'Timeline Preview',
        'timeline_desc' => 'Visualization of automatic reminders.',
    ],
    'email_templates' => [
        'presets' => [
            'formal' => 'Formal Welcome',
            'friendly' => 'Friendly Welcome',
            'minimal' => 'Simple Notification',
        ]
    ]
];

updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/ar/settings.php", $arNew);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/fr/settings.php", $frNew);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/en/settings.php", $enNew);

echo "Radical translation keys added!";
