<?php

$file = 'd:/new project/antigravty/edu/edu/Modules/Center/resources/views/settings/index.blade.php';
$content = file_get_contents($file);

// 1. Add Data Presets to the Payment Reminders Sub-Tab
$t1 = '/<!-- Payment Reminders Sub Tab -->\s*<div class="tab-pane fade" id="payment-reminders" role="tabpanel">\s*<!-- Payment Reminder Scheduling -->\s*<div class="mb-4 mt-2">\s*<h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"><\/i> \{\{ __\(\'center::settings\.tabs\.reminders\'\) \}\}<\/h4>\s*<\/div>/sm';

$r1 = <<<'EOF'
<!-- Payment Reminders Sub Tab -->
                                <div class="tab-pane fade" id="payment-reminders" role="tabpanel">
                                    @php
                                        $reminderPresets = [
                                            'email' => [
                                                'formal' => "نحيطكم علماً بأن مصروفات الطالب/ة {student_name} بمبلغ {amount} مستحقة بتاريخ {due_date}.\nيرجى التكرم بالسداد في الموعد المحدد لضمان استمرارية الخدمة التعليمية دون انقطاع.\nشاكرين لكم حسن تعاونكم.\n{center_name}",
                                                'friendly' => "أهلاً بكم في {center_name}،\nنود تذكيركم بأن موعد سداد مصروفات {student_name} هو {due_date} (المبلغ: {amount}).\nنسعد دائماً بوجودكم معنا ونتمنى للطالب دوام التوفيق.\nمع تحيات إدارة {center_name}",
                                                'urgent' => "تنبيه هام:\nنود إبلاغكم بأن مصروفات {student_name} بقيمة {amount} قد استحقت بالفعل بتاريخ {due_date}.\nيرجى سرعة السداد لتجنب توقف الحساب أو الخدمات.\nإذا كنتم قد سددتم بالفعل، يرجى تجاهل هذه الرسالة.\n{center_name}"
                                            ],
                                            'whatsapp' => [
                                                'formal' => "تذكير رسمي: مصروفات {student_name} بمبلغ {amount} مستحقة بتاريخ {due_date}. يرجى السداد لضمان استمرار الخدمة. {center_name}",
                                                'friendly' => "أهلاً بك! نود تذكيرك بموعد سداد مصروفات {student_name} بتاريخ {due_date}. نتمنى لكم يوماً سعيداً! 🌸 {center_name}",
                                                'urgent' => "تنبيه عاجل: مصروفات {student_name} مستحقة منذ {due_date}. يرجى السداد في أقرب وقت لتجنب انقطاع الخدمة. شكراً لك. {center_name}"
                                            ]
                                        ];
                                    @endphp
                                    <!-- Payment Reminder Scheduling -->
                                    <div class="mb-4 mt-2">
                                        <h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"></i> {{ __('center::settings.tabs.reminders') }}</h4>
                                    </div>
EOF;

$content = preg_replace($t1, $r1, $content);

// 2. Add Preset Buttons for Email Template
$t2 = '/<div class=\"mb-3\">\s*<div class=\"alert alert-light rounded-3 border mb-2 p-2\">\s*<small class=\"fw-bold text-muted\">\{\{ __\(\'center::settings\.reminders\.template_variables\'\) \}\}<\/small>\s*<div class=\"d-flex flex-wrap gap-1 mt-1\">\s*@foreach \(\[\'{student_name}\', \'{center_name}\', \'{amount}\', \'{due_date}\', \'{remaining}\', \'{group_name}\', \'{course_price}\'\] as \$var\)\s*<span class=\"badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small cursor-pointer\" onclick=\"insertVariable\(this, \'emailTemplateArea\'\)\">\{\{ \$var \}\}<\/span>\s*@endforeach\s*<\/div>\s*<\/div>\s*<textarea name=\"email_template\"/sm';

$r2 = <<<'EOF'
<div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.template_variables') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @foreach (['{student_name}', '{center_name}', '{amount}', '{due_date}', '{remaining}', '{group_name}', '{course_price}'] as $var)
                                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small cursor-pointer" onclick="insertVariable(this, 'emailTemplateArea')">{{ $var }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1">نماذج سريعة للبريد:</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['formal'] }}`)">رسمي</button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['friendly'] }}`)">ودي</button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['urgent'] }}`)">عاجل</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="email_template"
EOF;

$content = preg_replace($t2, $r2, $content);

// 3. Add Preset Buttons for WhatsApp Template
$t3 = '/<div class=\"mb-3\">\s*<div class=\"alert alert-light rounded-3 border mb-2 p-2\">\s*<small class=\"fw-bold text-muted\">\{\{ __\(\'center::settings\.reminders\.template_variables\'\) \}\}<\/small>\s*<div class=\"d-flex flex-wrap gap-1 mt-1\">\s*@foreach \(\[\'{student_name}\', \'{center_name}\', \'{amount}\', \'{due_date}\', \'{remaining}\'\] as \$var\)\s*<span class=\"badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small cursor-pointer\" onclick=\"insertVariable\(this, \'whatsappTemplateArea\'\)\">\{\{ \$var \}\}<\/span>\s*@endforeach\s*<\/div>\s*<\/div>\s*<textarea name=\"whatsapp_template\"/sm';

$r3 = <<<'EOF'
<div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.template_variables') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @foreach (['{student_name}', '{center_name}', '{amount}', '{due_date}', '{remaining}'] as $var)
                                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small cursor-pointer" onclick="insertVariable(this, 'whatsappTemplateArea')">{{ $var }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1">نماذج سريعة للواتساب:</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['formal'] }}`)">رسمي</button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['friendly'] }}`)">ودي</button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['urgent'] }}`)">عاجل</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="whatsapp_template"
EOF;

$content = preg_replace($t3, $r3, $content);

// 4. Add fillPreset JS function
$t4 = '/function insertVariable\(badge, textareaId\) \{/sm';
$r4 = <<<'EOF'
function fillPreset(textareaId, text) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    textarea.value = text;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function insertVariable(badge, textareaId) {
EOF;

$content = preg_replace($t4, $r4, $content);

file_put_contents($file, $content);
echo 'Done!';
