<?php

$file = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// 1. Sub-tabs
$content = str_replace('> رسائل الترحيب', '> {{ __(\'center::settings.reminders.sub_tabs.welcome\') }}', $content);
$content = str_replace('> إشعارات النظام', '> {{ __(\'center::settings.reminders.sub_tabs.system\') }}', $content);
$content = str_replace('> تذكيرات الدفع', '> {{ __(\'center::settings.reminders.sub_tabs.payment\') }}', $content);

// 2. Quick Templates Header
$content = str_replace('نماذج سريعة للبريد:', '{{ __(\'center::settings.reminders.quick_templates_email\') }}', $content);
$content = str_replace('نماذج سريعة للواتساب:', '{{ __(\'center::settings.reminders.quick_templates_whatsapp\') }}', $content);

// 3. Preset Buttons
$content = str_replace('">رسمي</button>', '">{{ __(\'center::settings.reminders.presets.formal\') }}</button>', $content);
$content = str_replace('">ودي</button>', '">{{ __(\'center::settings.reminders.presets.friendly\') }}</button>', $content);
$content = str_replace('">عاجل</button>', '">{{ __(\'center::settings.reminders.presets.urgent\') }}</button>', $content);

file_put_contents($file, $content);
echo 'Blade translations updated!';
