import re
import os

center_file = r"d:\new project\antigravty\edu\edu\Modules\Center\resources\views\settings\index.blade.php"
instructor_file = r"d:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\settings.blade.php"

with open(instructor_file, 'r', encoding='utf-8') as f:
    inst_content = f.read()

# Extract the Email section from Instructor
start_marker = "{{-- Tab 3: Email Templates --}}"
end_marker = "{{-- Tab 4: Payment Reminders --}}"
start_idx = inst_content.find(start_marker)
end_idx = inst_content.find(end_marker)

if start_idx == -1 or end_idx == -1:
    print("Could not find markers in instructor file")
    exit(1)

email_block = inst_content[start_idx:end_idx].strip()

# We need to rewrite name="..." attributes for the center module.
# The keys in instructor are direct, e.g., name="welcome_student_subject"
# In center, they must be name="settings[email_templates][welcome_student_subject]"
# Let's find all name="..." in this block
def replace_name_attr(match):
    name_val = match.group(1)
    if name_val in ['welcome_student_enabled', 'welcome_student_subject', 'welcome_student_body',
                    'welcome_guardian_enabled', 'welcome_guardian_subject', 'welcome_guardian_body',
                    'notif_payment_reminder_enabled', 'notif_payment_reminder_subject', 'notif_payment_reminder_body',
                    'notif_group_enrollment_enabled', 'notif_group_enrollment_subject', 'notif_group_enrollment_body',
                    'notif_payment_confirmed_enabled', 'notif_payment_confirmed_subject', 'notif_payment_confirmed_body']:
        return f'name="settings[email_templates][{name_val}]"'
    return match.group(0)

email_block = re.sub(r'name="([^"]+)"', replace_name_attr, email_block)

# Remove the form tag because the center template might have a different form action, or we can just update the action.
# Center action: action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}"
email_block = re.sub(r'action="[^"]+"', r'action="{{ route(\'center.settings.update\', [\'tenant\' => $tenant->domain ?? \'center\']) }}"', email_block)

# Rename the tab id to match what Center expects (id="email_templates")
email_block = email_block.replace('id="email"', 'id="email_templates"')

# Replace the active class logic. In Center it uses {{ $activeTab == 'email_templates' ? 'show active' : '' }}
email_block = email_block.replace('class="tab-pane fade"', 'class="tab-pane fade {{ $activeTab == \'email_templates\' ? \'show active\' : \'\' }}"')


# Now let's grab the CSS/JS styles from instructor
style_start = inst_content.find("<style>")
style_end = inst_content.find("</style>") + 8
style_block = inst_content[style_start:style_end]

script_start = inst_content.find("<script>", style_end)
script_end = inst_content.find("</script>", script_start) + 9
script_block = inst_content[script_start:script_end]


# Now process Center file
with open(center_file, 'r', encoding='utf-8') as f:
    center_content = f.read()

center_start_marker = "<!-- Email Templates Settings -->"
center_end_marker = "<!-- Privacy & GDPR Settings -->"

c_start = center_content.find(center_start_marker)
c_end = center_content.find(center_end_marker)

if c_start == -1 or c_end == -1:
    print("Could not find markers in center file")
    exit(1)

# Replace the email block
new_center_content = center_content[:c_start] + "<!-- Email Templates Settings -->\n" + email_block + "\n\n                        " + center_content[c_end:]

# Inject styles if not present
if "<style>" not in new_center_content:
    new_center_content = new_center_content.replace("@push('scripts')", style_block + "\n\n@push('scripts')")
else:
    # Append new styles to existing style block
    s_end = new_center_content.find("</style>")
    new_center_content = new_center_content[:s_end] + "\n" + style_block.replace("<style>", "").replace("</style>", "") + new_center_content[s_end:]

# Inject scripts
# Append to the end of existing scripts
script_insert_pos = new_center_content.rfind("</script>")
if script_insert_pos != -1:
    new_center_content = new_center_content[:script_insert_pos+9] + "\n\n" + script_block + new_center_content[script_insert_pos+9:]
else:
    new_center_content = new_center_content.replace("@endpush", script_block + "\n@endpush")


with open(center_file, 'w', encoding='utf-8') as f:
    f.write(new_center_content)

print("Done updating Center template.")
