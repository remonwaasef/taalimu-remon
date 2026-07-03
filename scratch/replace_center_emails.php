<?php

$center_file = 'Modules/Center/resources/views/settings/index.blade.php';
$instructor_file = 'Modules/Instructor/resources/views/settings.blade.php';

$inst_content = file_get_contents($instructor_file);

// Extract the Email section from Instructor
$start_marker = '{{-- Tab 3: Email Templates --}}';
$end_marker = '{{-- Tab 4: Payment Reminders --}}';
$start_idx = strpos($inst_content, $start_marker);
$end_idx = strpos($inst_content, $end_marker);

if ($start_idx === false || $end_idx === false) {
    exit('Could not find markers in instructor file');
}

$email_block = trim(substr($inst_content, $start_idx, $end_idx - $start_idx));

$keys = [
    'welcome_student_enabled', 'welcome_student_subject', 'welcome_student_body',
    'welcome_guardian_enabled', 'welcome_guardian_subject', 'welcome_guardian_body',
    'notif_payment_reminder_enabled', 'notif_payment_reminder_subject', 'notif_payment_reminder_body',
    'notif_group_enrollment_enabled', 'notif_group_enrollment_subject', 'notif_group_enrollment_body',
    'notif_payment_confirmed_enabled', 'notif_payment_confirmed_subject', 'notif_payment_confirmed_body',
];

foreach ($keys as $key) {
    $email_block = str_replace('name="'.$key.'"', 'name="settings[email_templates]['.$key.']"', $email_block);
}

// Update form action
$email_block = preg_replace('/action="[^"]+"/', 'action="{{ route(\'center.settings.update\', [\'tenant\' => $tenant->domain ?? \'center\']) }}"', $email_block);

// Update ID
$email_block = str_replace('id="email"', 'id="email_templates"', $email_block);

// Update tab-pane class
$email_block = str_replace('class="tab-pane fade"', 'class="tab-pane fade {{ $activeTab == \'email_templates\' ? \'show active\' : \'\' }}"', $email_block);

// Grab CSS
$style_start = strpos($inst_content, '<style>');
$style_end = strpos($inst_content, '</style>') + 8;
$style_block = substr($inst_content, $style_start, $style_end - $style_start);

// Grab JS
$script_start = strpos($inst_content, '<script>', $style_end);
$script_end = strpos($inst_content, '</script>', $script_start) + 9;
$script_block = substr($inst_content, $script_start, $script_end - $script_start);

// Center file
$center_content = file_get_contents($center_file);

$c_start_marker = '<!-- Email Templates Settings -->';
$c_end_marker = '<!-- Privacy & GDPR Settings -->';

$c_start = strpos($center_content, $c_start_marker);
$c_end = strpos($center_content, $c_end_marker);

if ($c_start === false || $c_end === false) {
    exit('Could not find markers in center file');
}

$new_center_content = substr($center_content, 0, $c_start)."<!-- Email Templates Settings -->\n".$email_block."\n\n                        ".substr($center_content, $c_end);

// Append styles if not exist
if (strpos($new_center_content, '<style>') === false) {
    $new_center_content = str_replace("@push('scripts')", $style_block."\n\n@push('scripts')", $new_center_content);
} else {
    $s_end = strpos($new_center_content, '</style>');
    $inner_style = str_replace(['<style>', '</style>'], '', $style_block);
    $new_center_content = substr_replace($new_center_content, "\n".$inner_style, $s_end, 0);
}

// Append scripts
$script_insert_pos = strrpos($new_center_content, '</script>');
if ($script_insert_pos !== false) {
    $new_center_content = substr_replace($new_center_content, "\n\n".$script_block, $script_insert_pos + 9, 0);
} else {
    $new_center_content = str_replace('@endpush', $script_block."\n@endpush", $new_center_content);
}

file_put_contents($center_file, $new_center_content);

echo "Done updating Center template.\n";
