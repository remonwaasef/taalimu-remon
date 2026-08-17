                        <div class="tab-pane fade" id="email" role="tabpanel">
                            @php
                                $emailSettings = ($tenant->settings ?? [])['email_templates'] ?? [];
                                $presets = config('email_templates.presets', []);
                                $defaultPresetKey = config('email_templates.default_preset', 'formal');
                                $defaultPreset = $presets[$defaultPresetKey] ?? [];
                            @endphp
                            <form action="{{ route('instructor.email-templates.update') }}" method="POST">
                                @csrf

                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-envelope me-2"></i> إعدادات البريد الإلكتروني</h5>
                                </div>
                                <p class="text-muted small mb-4">تحكم في رسائل الترحيب التلقائية التي يتم إرسالها عند تسجيل طالب جديد.</p>

                                {{-- Quick Preset Selector --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-magic me-2 text-warning"></i> اختر قالب جاهز</h6>
                                        <div class="row g-3">
                                            @foreach($presets as $key => $preset)
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100 text-center cursor-pointer preset-card" data-preset="{{ $key }}" style="cursor: pointer; transition: all 0.2s;">
                                                        <div class="mb-2">
                                                            <i class="{{ $preset['icon'] ?? 'fas fa-file-alt' }} fa-2x text-primary"></i>
                                                        </div>
                                                        <span class="fw-bold small">{{ $preset['name'] ?? $key }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Student Welcome Email --}}
                                <div class="row g-4">
                                    <div class="col-lg-7">
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-graduate me-2 text-info"></i> رسالة ترحيب الطالب</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="student_subject" data-body-id="student_body" data-default-subject="{{ $defaultPreset['student_subject'] ?? '' }}" data-default-body="{{ $defaultPreset['student_body'] ?? '' }}">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="welcome_student_enabled" value="0">
                                                            <input class="form-check-input" type="checkbox" name="welcome_student_enabled" value="1" id="studentEmailEnabled" {{ ($emailSettings['welcome_student_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="studentEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="welcome_student_subject" id="student_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? '' }}" placeholder="مرحباً بك في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    @php
                                                        $vars = [
                                                            'اسم_الطالب' => 'اسم الطالب',
                                                            'اسم_المركز' => 'اسم المركز',
                                                            'رابط_الدخول' => 'رابط الدخول',
                                                            'كلمة_المرور' => 'كلمة المرور',
                                                            'رقم_الهاتف' => 'رقم الهاتف',
                                                        ];
                                                    @endphp
                                                    @foreach($vars as $key => $label)
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="student_body" data-var="{{ '{' . $key . '}' }}">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $label }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="welcome_student_body" id="student_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة الترحيب هنا...">{{ $emailSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Guardian Welcome Email --}}
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-shield me-2 text-success"></i> رسالة ترحيب ولي الأمر</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="guardian_subject" data-body-id="guardian_body" data-default-subject="{{ $defaultPreset['guardian_subject'] ?? '' }}" data-default-body="{{ $defaultPreset['guardian_body'] ?? '' }}">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="welcome_guardian_enabled" value="0">
                                                            <input class="form-check-input" type="checkbox" name="welcome_guardian_enabled" value="1" id="guardianEmailEnabled" {{ ($emailSettings['welcome_guardian_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="guardianEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="welcome_guardian_subject" id="guardian_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '' }}" placeholder="تم تسجيل {اسم_الطالب} في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    @php
                                                        $gVars = array_merge($vars, ['اسم_ولي_الأمر' => 'اسم ولي الأمر', 'المرحلة' => 'المرحلة الدراسية']);
                                                    @endphp
                                                    @foreach($gVars as $key => $label)
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="guardian_body" data-var="{{ '{' . $key . '}' }}">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $label }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="welcome_guardian_body" id="guardian_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة ولي الأمر هنا...">{{ $emailSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        {{-- Live Preview --}}
                                        <div class="sticky-top" style="top: 2rem; z-index: 5;">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                <div class="card-header bg-dark py-3 px-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="d-flex gap-1">
                                                            <span class="rounded-circle bg-danger" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-warning" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-success" style="width:10px; height:10px;"></span>
                                                        </div>
                                                        <span class="text-white x-small opacity-50 ms-2">معاينة الرسالة (الآن)</span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0 bg-white">
                                                    <div class="p-3 border-bottom bg-light">
                                                        <div class="small text-muted mb-1">الموضوع:</div>
                                                        <div id="preview-subject" class="fw-bold">...</div>
                                                    </div>
                                                    <div class="p-4" style="min-height: 400px; font-family: sans-serif; line-height: 1.6;">
                                                        <div id="preview-body" style="white-space: pre-wrap;">...</div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light border-0 text-center py-3">
                                                    <span class="text-muted x-small italic"><i class="fas fa-magic me-1 text-primary"></i> تظهر الرموز في المعاينة كبيانات تجريبية للتوضيح فقط</span>
                                                </div>
                                            </div>

                                            <div class="mt-4 p-4 bg-primary-soft rounded-4 border border-primary border-opacity-10">
                                                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-lightbulb me-2"></i> نصيحة احترافية</h6>
                                                <p class="small text-dark mb-0">استخدم الرموز التلقائية لجعل رسائلك شخصية أكثر. الرسائل التي تبدأ باسم الطالب تحقق تفاعلاً أعلى بنسبة 40%!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ═══════════════════════════════════════════════ --}}
                                {{-- Section 2: Event-Based Email Notifications    --}}
                                {{-- ═══════════════════════════════════════════════ --}}
                                <div class="mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> إشعارات البريد التلقائية</h5>
                                    </div>
                                    <p class="text-muted small mb-4">فعّل أو عطّل إرسال بريد إلكتروني تلقائي عند حدوث أحداث معينة. يمكنك تخصيص نص كل رسالة.</p>

                                    {{-- Accordion for each notification type --}}
                                    <div class="accordion" id="emailNotificationsAccordion">

                                        {{-- 1. Payment Reminder --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_reminder">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-clock text-warning"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">تذكير بموعد الدفع</span>
                                                            <small class="text-muted fw-normal">يُرسل للطالب أو ولي الأمر قبل موعد السداد</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_reminder" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_reminder_subject" data-body-id="notif_payment_reminder_body" data-default-subject="تذكير بسداد مصروفات {اسم_الطالب} - {اسم_المركز}" data-default-body="نذكركم بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\n\nيرجى السداد في الموعد المحدد لضمان استمرار الخدمة.\n\nشكراً لتعاونكم,\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="notif_payment_reminder_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_payment_reminder_enabled" value="1" id="notifPaymentReminder" {{ ($emailSettings['notif_payment_reminder_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentReminder">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_reminder_subject" name="notif_payment_reminder_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_payment_reminder_subject'] ?? 'تذكير بسداد مصروفات {اسم_الطالب} - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $payVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ'=>'المبلغ المستحق','تاريخ_الاستحقاق'=>'تاريخ الاستحقاق','رابط_الدخول'=>'رابط الدخول']; @endphp
                                                        @foreach($payVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_reminder_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_payment_reminder_body" id="notif_payment_reminder_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_payment_reminder_body'] ?? "نذكركم بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\n\nيرجى السداد في الموعد المحدد لضمان استمرار الخدمة.\n\nشكراً لتعاونكم,\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 2. New Group Enrollment --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_group_enrollment">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-info bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-user-plus text-info"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">الاشتراك في مجموعة جديدة</span>
                                                            <small class="text-muted fw-normal">يُرسل عند إضافة طالب لمجموعة أو كورس جديد</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_group_enrollment" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_group_enrollment_subject" data-body-id="notif_group_enrollment_body" data-default-subject="تم تسجيلك في مجموعة جديدة - {اسم_المركز}" data-default-body="مرحباً {اسم_الطالب}،\n\nتم تسجيلك في مجموعة جديدة: {اسم_المجموعة}\n\nيمكنك الدخول للمنصة من خلال:\n{رابط_الدخول}\n\nنتمنى لك التوفيق!\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="notif_group_enrollment_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_group_enrollment_enabled" value="1" id="notifGroupEnrollment" {{ ($emailSettings['notif_group_enrollment_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifGroupEnrollment">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_group_enrollment_subject" name="notif_group_enrollment_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_group_enrollment_subject'] ?? 'تم تسجيلك في مجموعة جديدة - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $grpVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','اسم_المجموعة'=>'اسم المجموعة','سعر_الدورة'=>'سعر الدورة','رابط_الدخول'=>'رابط الدخول']; @endphp
                                                        @foreach($grpVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_group_enrollment_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_group_enrollment_body" id="notif_group_enrollment_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_group_enrollment_body'] ?? "مرحباً {اسم_الطالب}،\n\nتم تسجيلك في مجموعة جديدة: {اسم_المجموعة}\n\nيمكنك الدخول للمنصة من خلال:\n{رابط_الدخول}\n\nنتمنى لك التوفيق!\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 3. Payment Confirmation --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_confirmed">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">تأكيد استلام مبلغ</span>
                                                            <small class="text-muted fw-normal">يُرسل عند تسجيل دفعة مالية جديدة للطالب</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_confirmed" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_confirmed_subject" data-body-id="notif_payment_confirmed_body" data-default-subject="تأكيد استلام دفعة - {اسم_المركز}" data-default-body="مرحباً {اسم_الطالب}،\n\nنؤكد استلام دفعة مالية بالتفاصيل التالية:\n• المبلغ: {المبلغ_المدفوع}\n• التاريخ: {تاريخ_الدفع}\n• طريقة الدفع: {طريقة_الدفع}\n• المتبقي: {المتبقي}\n\nشكراً لالتزامكم.\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="notif_payment_confirmed_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_payment_confirmed_enabled" value="1" id="notifPaymentConfirmed" {{ ($emailSettings['notif_payment_confirmed_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentConfirmed">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_confirmed_subject" name="notif_payment_confirmed_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $confVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ_المدفوع'=>'المبلغ المدفوع','تاريخ_الدفع'=>'تاريخ الدفع','المتبقي'=>'المبلغ المتبقي','طريقة_الدفع'=>'طريقة الدفع']; @endphp
                                                        @foreach($confVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_confirmed_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_payment_confirmed_body" id="notif_payment_confirmed_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_payment_confirmed_body'] ?? "مرحباً {اسم_الطالب}،\n\nنؤكد استلام دفعة مالية بالتفاصيل التالية:\n• المبلغ: {المبلغ_المدفوع}\n• التاريخ: {تاريخ_الدفع}\n• طريقة الدفع: {طريقة_الدفع}\n• المتبقي: {المتبقي}\n\nشكراً لالتزامكم.\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> حفظ إعدادات البريد
                                    </button>
                                </div>
                            </form>
                            
                            <form action="{{ route('instructor.email-templates.reset') }}" method="POST" class="d-inline-block mt-3" id="deleteRowForm_1">
                                @csrf
                                <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> إعادة الضبط للافتراضي
                                </button>
                            </form>
                        </div>
