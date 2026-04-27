<?php

$file_path = "d:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php";
$content = file_get_contents($file_path);

// We need to wrap the contents of the #reminders tab into 3 sub-tabs.
// Section 1: From `<div class="mb-4">\s*<h4 class="fw-bold" style="color: #3A0CA3;">` ... until `{{-- ═══════════════════════════════════════════════ --}}` (which starts Event-Based Notifications)
// Section 2: From `{{-- ═══════════════════════════════════════════════ --}}` ... until `<hr class="my-5 border-secondary opacity-25">\s*<!-- Payment Reminder Scheduling -->`
// Section 3: From `<!-- Payment Reminder Scheduling -->` ... until the end of the form.

// However, the form for email templates wraps BOTH Section 1 and Section 2.
// <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
// This form starts at line 203 and ends at line 524.
// Section 3 has its own form starting around 550 and ending at 859.
// So we can safely put Section 1 and Section 2 inside their own sub-tabs, BUT they share the same `<form>` tag!
// We can wrap the inner parts, OR move the `<form>` to wrap the entire `reminders` tab, OR have each sub-tab have its own form.
// Actually, it's totally fine to have a `<form>` wrap multiple `.tab-pane` elements! Bootstrap tab-panes just hide/show content. If they are all inside one form, submitting it will submit all inputs. BUT Section 3 has a DIFFERENT route `center.settings.update-reminders`! So Section 1 & 2 are one form, Section 3 is another form.

// Let's modify the HTML by replacing the start of the #reminders tab.
$t1 = '/<!-- Combined Tab: Email Templates & Reminders -->\s*<div class="tab-pane fade \{\{ in_array\(\$activeTab, \[\'reminders\', \'email_templates\'\]\) \? \'show active\' : \'\' \}\}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">\s*<div class="mb-4">\s*<h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"><\/i> \{\{ __\(\'center::settings\.tabs\.email_templates\'\) \}\}<\/h4>\s*<p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات<\/p>\s*<\/div>/sm';

$r1 = <<<EOF
<!-- Combined Tab: Email Templates & Reminders -->
                        <div class="tab-pane fade {{ in_array(\$activeTab, ['reminders', 'email_templates']) ? 'show active' : '' }}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
                            
                            <!-- Sub Tabs Nav -->
                            <ul class="nav nav-pills mb-4 bg-light p-2 rounded-4 d-flex justify-content-center gap-2" id="remindersSubTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill px-4 fw-bold" id="welcome-emails-tab" data-bs-toggle="pill" data-bs-target="#welcome-emails" type="button" role="tab">
                                        <i class="fas fa-handshake me-2"></i> رسائل الترحيب
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="system-notifs-tab" data-bs-toggle="pill" data-bs-target="#system-notifs" type="button" role="tab">
                                        <i class="fas fa-bell me-2"></i> إشعارات النظام
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="payment-reminders-tab" data-bs-toggle="pill" data-bs-target="#payment-reminders" type="button" role="tab">
                                        <i class="fas fa-calendar-check me-2"></i> تذكيرات الدفع
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="remindersSubTabsContent">
                                <!-- Welcome Emails Sub Tab -->
                                <div class="tab-pane fade show active" id="welcome-emails" role="tabpanel">
                                    <div class="mb-4">
                                        <h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> {{ __('center::settings.tabs.email_templates') }}</h4>
                                        <p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات</p>
                                    </div>
EOF;
$content = preg_replace($t1, $r1, $content);

// Wrap Section 2 (System Notifications)
$t2 = '/\{\{-- ═══════════════════════════════════════════════ --\}\}\s*\{\{-- Section 2: Event-Based Email Notifications    --\}\}\s*\{\{-- ═══════════════════════════════════════════════ --\}\}/sm';
$r2 = <<<EOF
                                </div> <!-- Close welcome-emails sub tab -->
                                
                                <!-- System Notifications Sub Tab -->
                                <div class="tab-pane fade" id="system-notifs" role="tabpanel">
                                    {{-- ═══════════════════════════════════════════════ --}}
                                    {{-- Section 2: Event-Based Email Notifications    --}}
                                    {{-- ═══════════════════════════════════════════════ --}}
EOF;
$content = preg_replace($t2, $r2, $content);

// Wrap Section 3 (Payment Reminders)
// The end of Section 2 has:
// </form>
// <form action="..." method="POST" ... reset-email-templates> ... </form>
// <hr class="my-5 border-secondary opacity-25">
// <!-- Payment Reminder Scheduling -->
$t3 = '/<\/form>\s*<hr class="my-5 border-secondary opacity-25">\s*<!-- Payment Reminder Scheduling -->\s*<div class="mb-4 mt-5">\s*<h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"><\/i> \{\{ __\(\'center::settings\.tabs\.reminders\'\) \}\}<\/h4>\s*<\/div>/sm';
$r3 = <<<EOF
                            </form>
                                </div> <!-- Close system-notifs sub tab -->
                                
                                <!-- Payment Reminders Sub Tab -->
                                <div class="tab-pane fade" id="payment-reminders" role="tabpanel">
                                    <!-- Payment Reminder Scheduling -->
                                    <div class="mb-4 mt-2">
                                        <h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"></i> {{ __('center::settings.tabs.reminders') }}</h4>
                                    </div>
EOF;
$content = preg_replace($t3, $r3, $content);

// Close the Sub Tab container before the Academic Settings
$t4 = '/<\/form>\s*<\/div>\s*<!-- Academic Settings -->/sm';
$r4 = <<<EOF
                            </form>
                                </div> <!-- Close payment-reminders sub tab -->
                            </div> <!-- Close tab-content -->
                        </div>

                        <!-- Academic Settings -->
EOF;
$content = preg_replace($t4, $r4, $content);

file_put_contents($file_path, $content);
echo "Done!";
