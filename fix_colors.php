<?php

$file = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// 1. Add custom style
$style = "@push('styles')
<style>
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1) !important; border-color: rgba(23, 162, 184, 0.2) !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; border-color: rgba(220, 53, 69, 0.2) !important; }
    .active-reminder-info { background-color: rgba(0, 180, 216, 0.15) !important; border-color: #00b4d8 !important; }
    .active-reminder-danger { background-color: rgba(231, 76, 60, 0.15) !important; border-color: #e74c3c !important; }
</style>
@endpush";

if (strpos($content, '.bg-info-soft') === false) {
    $content = $style . "\n" . $content;
}

// 2. Update Pre-Due (Info) section
$content = str_replace(
    "{{ \$reminder['enabled'] ? 'border-info bg-info bg-opacity-10' : 'bg-light' }}",
    "{{ \$reminder['enabled'] ? 'active-reminder-info' : 'bg-light' }}",
    $content
);
$content = str_replace(
    "toggleReminderStyle(this, 'preReminder{{ \$index }}', 'border-info bg-info')",
    "toggleReminderStyle(this, 'preReminder{{ \$index }}', 'active-reminder-info')",
    $content
);

// 3. Update Post-Due (Danger) section
$content = str_replace(
    "{{ \$reminder['enabled'] ? 'border-danger bg-danger bg-opacity-10' : 'bg-light' }}",
    "{{ \$reminder['enabled'] ? 'active-reminder-danger' : 'bg-light' }}",
    $content
);
$content = str_replace(
    "toggleReminderStyle(this, 'postReminder{{ \$index }}', 'border-danger bg-danger')",
    "toggleReminderStyle(this, 'postReminder{{ \$index }}', 'active-reminder-danger')",
    $content
);

// 4. Update JS function
$oldJs = "function toggleReminderStyle(checkbox, elementId, colorClass) {
    const el = document.getElementById(elementId);
    if (!el) return;
    if (checkbox.checked) {
        el.classList.remove('bg-light');
        el.classList.add(colorClass.split(' ')[0], colorClass.split(' ')[1], 'bg-opacity-10');
    } else {
        el.classList.add('bg-light');
        el.classList.remove(colorClass.split(' ')[0], colorClass.split(' ')[1], 'bg-opacity-10');
    }
}";

$newJs = "function toggleReminderStyle(checkbox, elementId, colorClass) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const classes = colorClass.split(' ');
    if (checkbox.checked) {
        el.classList.remove('bg-light');
        el.classList.add(...classes);
    } else {
        el.classList.add('bg-light');
        el.classList.remove(...classes);
    }
}";

$content = str_replace($oldJs, $newJs, $content);

file_put_contents($file, $content);
echo "Colors updated successfully!";
