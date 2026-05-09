<?php
$file = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// 1. Remove the nav-item for email_templates
$navStart = strpos($content, '<li class="nav-item" role="presentation">');
$navEnd = 0;
$navToReplace = '';
while (($pos = strpos($content, '<li class="nav-item" role="presentation">', $navEnd)) !== false) {
    $endPos = strpos($content, '</li>', $pos) + 5;
    $chunk = substr($content, $pos, $endPos - $pos);
    if (strpos($chunk, 'email_templates-tab') !== false) {
        $navToReplace = $chunk;
        break;
    }
    $navEnd = $endPos;
}

if ($navToReplace) {
    $content = str_replace($navToReplace, '', $content);
}

// 2. Extract the email_templates tab pane
$emailPaneStart = strpos($content, '<!-- Email Templates Settings -->');
if ($emailPaneStart === false) {
    // fallback
    $emailPaneStart = strpos($content, '<div class="tab-pane fade {{ $activeTab == \'email_templates\' ? \'show active\' : \'\' }}" id="email_templates"');
}
if ($emailPaneStart !== false) {
    $emailPaneEnd = strpos($content, '<!-- Reminder Scheduling Settings -->', $emailPaneStart);
    if ($emailPaneEnd === false) {
        $emailPaneEnd = strpos($content, '<!-- Privacy & GDPR Settings -->', $emailPaneStart);
    }
    $emailPaneChunk = substr($content, $emailPaneStart, $emailPaneEnd - $emailPaneStart);
    
    // Remove the chunk from its original place
    $content = str_replace($emailPaneChunk, '', $content);
    
    // 3. Insert the emailPaneChunk inside the reminders pane.
    // The reminders pane starts with <!-- Reminder Scheduling Settings -->
    $remindersPaneStart = strpos($content, '<!-- Reminder Scheduling Settings -->');
    // We want to insert it at the END of the reminders tab, before the next tab (Privacy & GDPR Settings)
    $remindersPaneEnd = strpos($content, '<!-- Privacy & GDPR Settings -->', $remindersPaneStart);
    
    // Modify the email pane to NOT be a tab-pane, but just a card or div inside the reminders pane.
    $cleanedEmailPane = str_replace('<div class="tab-pane fade {{ $activeTab == \'email_templates\' ? \'show active\' : \'\' }}" id="email_templates" role="tabpanel" aria-labelledby="email_templates-tab">', '<div class="mt-5">', $emailPaneChunk);
    
    // The original pane ends with a </div> which matches the removed tab-pane div.
    // Since we replaced the tab-pane with a div, the closing </div> is still correct.
    
    // Let's add a divider or header
    $header = "
                            <hr class=\"my-5 border-secondary opacity-25\">
                            <div class=\"mb-4\">
                                <h4 class=\"fw-bold\" style=\"color: #3A0CA3;\"><i class=\"fas fa-envelope-open-text me-2\"></i> " . "{{ __('center::settings.tabs.email_templates') }}" . "</h4>
                                <p class=\"text-muted\">" . "قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات" . "</p>
                            </div>\n";
    
    // Insert into content right before the end of the reminders tab pane.
    // Wait, the reminders pane ends with a `</div>`.
    // Let's find the closing tag of the reminders form.
    $remindersFormEnd = strpos($content, '</form>', $remindersPaneStart);
    $insertPos = strpos($content, '</div>', $remindersFormEnd);
    
    if ($insertPos !== false) {
        $content = substr($content, 0, $insertPos) . "\n" . $header . $cleanedEmailPane . "\n" . substr($content, $insertPos);
    }
}

file_put_contents($file, $content);
echo "MERGE COMPLETE\n";
