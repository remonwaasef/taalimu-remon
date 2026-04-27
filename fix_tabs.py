import re

file_path = "d:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace 1
t1 = r'<li class="nav-item" role="presentation">\s*<button class="nav-link \{\{ \$activeTab == \'reminders\' \? \'active\' : \'\' \}\} py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="\{\{ \$activeTab == \'reminders\' \? \'true\' : \'false\' \}\}">\s*<i class="fas fa-calendar-check me-2 text-warning"></i> \{\{ __\(\'center::settings\.tabs\.reminders\'\) \}\}\s*</button>\s*</li>'
r1 = """<li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'active' : '' }} py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="{{ in_array($activeTab, ['reminders', 'email_templates']) ? 'true' : 'false' }}">
                                <i class="fas fa-bullhorn me-2 text-warning"></i> {{ __('center::settings.tabs.email_templates') }} / {{ __('center::settings.tabs.reminders') }}
                            </button>
                        </li>"""
content = re.sub(t1, r1, content)

# Replace 2
t2 = r'                            </form>\s*<hr class="my-5 border-secondary opacity-25">\s*<div class="mb-4">\s*<h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> \{\{ __\(\'center::settings\.tabs\.email_templates\'\) \}\}</h4>\s*<p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات</p>\s*</div>\s*<!-- Email Templates Settings -->\s*\{\{-- Tab 3: Email Templates --\}\}\s*<div class="tab-pane fade \{\{ \$activeTab == \'email_templates\' \? \'show active\' : \'\' \}\}" id="email_templates" role="tabpanel">'
r2 = """                            </form>
                        </div> <!-- Closes general tab -->

                        <!-- Combined Tab: Email Templates & Reminders -->
                        <div class="tab-pane fade {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'show active' : '' }}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
                            <div class="mb-4">
                                <h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> {{ __('center::settings.tabs.email_templates') }}</h4>
                                <p class="text-muted">قوالب البريد الإلكتروني الخاصة بالنظام والإشعارات</p>
                            </div>"""
content = re.sub(t2, r2, content)

# Replace 3
t3 = r'                        </div>\s*</div>\s*<!-- Academic Settings -->'
r3 = """                        </div>

                        <!-- Academic Settings -->"""
content = re.sub(t3, r3, content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Done!")
