<?php

function updateLangFile($path, $newKeys)
{
    if (! file_exists($path)) {
        return;
    }
    $content = include $path;

    // Recursive merge/update
    $content = array_replace_recursive($content, $newKeys);

    $export = var_export($content, true);
    // Convert array() to []
    $export = preg_replace('/array \(/', '[', $export);
    $export = preg_replace('/\)/', ']', $export);
    $export = preg_replace('/=> \n\s+\[/', '=> [', $export);

    file_put_contents($path, "<?php\n\nreturn ".$export.";\n");
}

// 1. Analytics Files
$analyticsKeys = [
    'ar' => [
        'discounts_granted' => 'الخصومات الممنوحة',
        'total_discounts_amount' => 'إجمالي مبلغ الخصومات',
        'discounts_log' => 'سجل الخصومات',
        'discount_value' => 'قيمة الخصم',
        'final_after_discount' => 'الإجمالي بعد الخصم',
        'no_discounts_recorded' => 'لا يوجد خصومات مسجلة',
    ],
    'fr' => [
        'discounts_granted' => 'Remises Accordées',
        'total_discounts_amount' => 'Montant Total des Remises',
        'discounts_log' => 'Sujet des Remises',
        'discount_value' => 'Valeur de la Remise',
        'final_after_discount' => 'Total après Remise',
        'no_discounts_recorded' => 'Aucune remise enregistrée',
    ],
    'en' => [
        'discounts_granted' => 'Discounts Granted',
        'total_discounts_amount' => 'Total Discounts Amount',
        'discounts_log' => 'Discounts Log',
        'discount_value' => 'Discount Value',
        'final_after_discount' => 'Final After Discount',
        'no_discounts_recorded' => 'No discounts recorded',
    ],
];

foreach ($analyticsKeys as $lang => $keys) {
    updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/$lang/analytics.php", $keys);
}

// 2. Settings Files (Add missing reminder sub-keys)
$settingsKeys = [
    'ar' => [
        'reminders' => [
            'sub_tabs' => [
                'welcome' => 'رسائل الترحيب',
                'system' => 'إشعارات النظام',
                'payment' => 'تذكيرات الدفع',
            ],
            'quick_templates_email' => 'نماذج سريعة للبريد:',
            'quick_templates_whatsapp' => 'نماذج سريعة للواتساب:',
            'presets' => [
                'formal' => 'رسمي',
                'friendly' => 'ودي',
                'urgent' => 'عاجل',
            ],
        ],
    ],
    'fr' => [
        'reminders' => [
            'sub_tabs' => [
                'welcome' => 'Messages de Bienvenue',
                'system' => 'Notifications Système',
                'payment' => 'Rappels de Paiement',
            ],
            'quick_templates_email' => 'Modèles rapides E-mail :',
            'quick_templates_whatsapp' => 'Modèles rapides WhatsApp :',
            'presets' => [
                'formal' => 'Formel',
                'friendly' => 'Amical',
                'urgent' => 'Urgent',
            ],
        ],
    ],
    'en' => [
        'reminders' => [
            'sub_tabs' => [
                'welcome' => 'Welcome Messages',
                'system' => 'System Notifications',
                'payment' => 'Payment Reminders',
            ],
            'quick_templates_email' => 'Quick Email Templates:',
            'quick_templates_whatsapp' => 'Quick WhatsApp Templates:',
            'presets' => [
                'formal' => 'Formal',
                'friendly' => 'Friendly',
                'urgent' => 'Urgent',
            ],
        ],
    ],
];

foreach ($settingsKeys as $lang => $keys) {
    updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/$lang/settings.php", $keys);
}

echo 'All language files updated successfully!';
