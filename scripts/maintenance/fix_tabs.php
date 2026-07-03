<?php

$file_path = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file_path);

// Replace 1
$t1 = '/<li class="nav-item" role="presentation">\s*<button class="nav-link \{\{ \$activeTab == \'reminders\' \? \'active\' : \'\' \}\} py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="\{\{ \$activeTab == \'reminders\' \? \'true\' : \'false\' \}\}">\s*<i class="fas fa-calendar-check me-2 text-warning"><\/i> \{\{ __\(\'center::settings\.tabs\.reminders\'\) \}\}\s*<\/button>\s*<\/li>/sm';
$r1 = <<<'EOF'
<li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'active' : '' }} py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="{{ in_array($activeTab, ['reminders', 'email_templates']) ? 'true' : 'false' }}">
                                <i class="fas fa-bullhorn me-2 text-warning"></i> {{ __('center::settings.tabs.email_templates') }} / {{ __('center::settings.tabs.reminders') }}
                            </button>
                        </li>
EOF;
$content = preg_replace($t1, $r1, $content);

// Replace 2
$t2 = '/<\/form>\s*<hr class="my-5 border-secondary opacity-25">\s*<div class="mb-4">\s*<h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"><\/i> \{\{ __\(\'center::settings\.tabs\.email_templates\'\) \}\}<\/h4>\s*<p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات<\/p>\s*<\/div>\s*<!-- Email Templates Settings -->\s*\{\{-- Tab 3: Email Templates --\}\}\s*<div class="tab-pane fade \{\{ \$activeTab == \'email_templates\' \? \'show active\' : \'\' \}\}" id="email_templates" role="tabpanel">/sm';
$r2 = <<<'EOF'
</form>
                        </div> <!-- Closes general tab -->

                        <!-- Combined Tab: Email Templates & Reminders -->
                        <div class="tab-pane fade {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'show active' : '' }}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
                            <div class="mb-4">
                                <h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> {{ __('center::settings.tabs.email_templates') }}</h4>
                                <p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات</p>
                            </div>
EOF;
$content = preg_replace($t2, $r2, $content);

// Replace 3
$t3 = '/<\/div>\s*<\/div>\s*<!-- Academic Settings -->/sm';
$r3 = <<<'EOF'
</div>

                        <!-- Academic Settings -->
EOF;
$content = preg_replace($t3, $r3, $content);

file_put_contents($file_path, $content);
echo 'Done!';
