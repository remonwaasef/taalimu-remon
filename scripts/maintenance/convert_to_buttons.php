<?php

$file = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// 1. Update Email Variables (Change badge to button)
$oldEmailBadge = '<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small cursor-pointer" onclick="insertVariable(this, \'emailTemplateArea\')">{{ $var }}</span>';
$newEmailButton = '<button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0 small" onclick="insertVariable(this, \'emailTemplateArea\')">{{ $var }}</button>';

$content = str_replace($oldEmailBadge, $newEmailButton, $content);

// 2. Update WhatsApp Variables (Change badge to button)
$oldWaBadge = '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small cursor-pointer" onclick="insertVariable(this, \'whatsappTemplateArea\')">{{ $var }}</span>';
$newWaButton = '<button type="button" class="btn btn-xs btn-outline-success rounded-pill px-2 py-0 small" onclick="insertVariable(this, \'whatsappTemplateArea\')">{{ $var }}</button>';

$content = str_replace($oldWaBadge, $newWaButton, $content);

file_put_contents($file, $content);
echo 'Variables converted to buttons!';
