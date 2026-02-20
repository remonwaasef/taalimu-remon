<div id="onboardingWizard" class="onboarding-overlay" style="{{ $onboardingIncomplete ? '' : 'display: none;' }}">
    <div class="wizard-card animate__animated animate__zoomIn animate__faster mx-3">
        <div class="row g-0">
            <!-- Sidebar Stepper (Visual only) -->
            <div class="col-md-3 bg-primary bg-opacity-10 p-4 border-end d-none d-md-block">
                <div class="d-flex flex-column h-100">
                    <div class="text-center mb-4">
                        <i class="fas fa-rocket fa-3x text-primary shadow-sm"></i>
                        <h6 class="mt-3 fw-bold text-primary">{{ __('center::dashboard.launchpad.title', ['name' => '']) }}</h6>
                    </div>
                    <div class="wizard-steps flex-grow-1">
                        <div class="step-item active mb-4 d-flex align-items-center" data-step="1">
                            <div class="step-icon me-3">1</div>
                            <span class="small fw-bold">النظام التعليمي</span>
                        </div>
                        <div class="step-item mb-4 d-flex align-items-center opacity-50" data-step="2">
                            <div class="step-icon me-3">2</div>
                            <span class="small fw-bold">المدرسين</span>
                        </div>
                        <div class="step-item mb-4 d-flex align-items-center opacity-50" data-step="3">
                            <div class="step-icon me-3">3</div>
                            <span class="small fw-bold">الدورات</span>
                        </div>
                        <div class="step-item mb-4 d-flex align-items-center opacity-50" data-step="4">
                            <div class="step-icon me-3">4</div>
                            <span class="small fw-bold">الطلاب</span>
                        </div>
                        <div class="step-item d-flex align-items-center opacity-50" data-step="5">
                            <div class="step-icon me-3">5</div>
                            <span class="small fw-bold">تم الإعداد</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9 p-4 p-lg-5 position-relative">
                <!-- Step 1: System Selection -->
                <div class="wizard-step" id="step1">
                    <h4 class="fw-bold mb-3">اختر نظامك التعليمي</h4>
                    <p class="text-muted small mb-4">اختر القالب التعليمي المناسب لمركزك لإنشاء الصفوف والمراحل تلقائياً.</p>
                    
                    <div class="row g-3">
                        @foreach(config('academic.templates', []) as $key => $template)
                        <div class="col-6">
                            <div class="template-card p-3 border rounded-4 text-center cursor-pointer hover-shadow" onclick="selectTemplate('{{ $key }}')">
                                <i class="fas fa-university fa-2x mb-3 text-primary"></i>
                                <h6 class="fw-bold mb-0 small">{{ __($template['name']) }}</h6>
                                <input type="radio" name="template" value="{{ $key }}" class="d-none">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-end">
                        <button class="btn btn-primary rounded-pill px-5 fw-bold" onclick="saveStep1()" id="btnStep1">تبدأ الآن</button>
                    </div>
                </div>

                <!-- Step 2: Instructor -->
                <div class="wizard-step d-none" id="step2">
                    <h4 class="fw-bold mb-3">أضف أول مدرس</h4>
                    <p class="text-muted small mb-4">بدأ ببناء فريقك التعليمي بإضافة أول مدرس للمركز.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">اسم المدرس</label>
                        <input type="text" id="instructor_name" class="form-control rounded-pill px-3" placeholder="مثلاً: د. أحمد محمد">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">رقم الهاتف</label>
                        <input type="text" id="instructor_phone" class="form-control rounded-pill px-3" placeholder="01xxxxxxxxx">
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-between">
                        <button class="btn btn-light rounded-pill px-4 fw-bold" onclick="prevStep(1)">رجوع</button>
                        <button class="btn btn-primary rounded-pill px-5 fw-bold" onclick="saveStep2()" id="btnStep2">التالي</button>
                    </div>
                </div>

                <!-- Step 3: Course -->
                <div class="wizard-step d-none" id="step3">
                    <h4 class="fw-bold mb-3">أنشئ أول دورة تعليمية</h4>
                    <p class="text-muted small mb-4">حدد المادة أو الدورة التي سيتم تدريسها.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">اسم الدورة / المادة</label>
                        <input type="text" id="course_title" class="form-control rounded-pill px-3" placeholder="مثلاً: كيمياء الصف الثالث">
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-between">
                        <button class="btn btn-light rounded-pill px-4 fw-bold" onclick="prevStep(2)">رجوع</button>
                        <button class="btn btn-primary rounded-pill px-5 fw-bold" onclick="saveStep3()" id="btnStep3">التالي</button>
                    </div>
                </div>

                <!-- Step 4: Student -->
                <div class="wizard-step d-none" id="step4">
                    <h4 class="fw-bold mb-3">سجل أول طالب</h4>
                    <p class="text-muted small mb-4">الخطوة الأخيرة هي إضافة طالب لتجربة النظام.</p>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold">اسم الطالب</label>
                        <input type="text" id="student_name" class="form-control rounded-pill px-3" placeholder="مثلاً: محمد علي">
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold">اختر الصف الدراسي</label>
                        <select id="student_grade_id" class="form-select rounded-pill px-3">
                            <!-- Populated via JS -->
                        </select>
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-between">
                        <button class="btn btn-light rounded-pill px-4 fw-bold" onclick="prevStep(3)">رجوع</button>
                        <button class="btn btn-primary rounded-pill px-5 fw-bold" onclick="saveStep4()" id="btnStep4">التالي</button>
                    </div>
                </div>

                <!-- Step 5: Success -->
                <div class="wizard-step d-none text-center py-5" id="step5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success animate__animated animate__bounceIn"></i>
                    </div>
                    <h3 class="fw-bold mb-3">تم الإعداد بنجاح!</h3>
                    <p class="text-muted mb-4">لقد قمت بإعداد مركزك التعليمي بنجاح. أنت الآن جاهز لبدء استخدام كافة مميزات المنصة.</p>
                    
                    <button class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow-sm" onclick="finishOnboarding()">دخول لوحة التحكم</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .template-card {
        transition: all 0.2s;
        border: 2px solid transparent !important;
    }
    .template-card:hover { border-color: var(--primary-color) !important; background: rgba(58, 12, 163, 0.05); }
    .template-card.selected { border-color: var(--primary-color) !important; background: rgba(58, 12, 163, 0.1); }
    
    .step-icon {
        width: 32px;
        height: 32px;
        background: #fff;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }
    .step-item.active .step-icon {
        background: var(--primary-color);
        color: #fff;
    }
</style>

<script>
    let onboardingData = {
        template: '',
        instructor_id: null,
        course_id: null,
        grade_id: null
    };

    function selectTemplate(key) {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        onboardingData.template = key;
    }

    function saveStep1() {
        if (!onboardingData.template) return Toast.fire({ icon: 'error', title: 'يرجى اختيار نظام تعليمي' });
        
        const btn = document.getElementById('btnStep1');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...';

        fetch("{{ route('center.onboarding.template') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ template_key: onboardingData.template })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                nextStep(2);
            } else {
                Toast.fire({ icon: 'error', title: data.message });
                btn.disabled = false;
                btn.innerText = 'تبدأ الآن';
            }
        });
    }

    function saveStep2() {
        const name = document.getElementById('instructor_name').value;
        const phone = document.getElementById('instructor_phone').value;
        if (!name) return Toast.fire({ icon: 'error', title: 'يرجى إدخال اسم المدرس' });

        const btn = document.getElementById('btnStep2');
        btn.disabled = true;

        fetch("{{ route('center.onboarding.instructor') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name, phone: phone })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                onboardingData.instructor_id = data.id;
                nextStep(3);
            }
            else { btn.disabled = false; Toast.fire({ icon: 'error', title: data.message }); }
        });
    }

    function saveStep3() {
        const title = document.getElementById('course_title').value;
        if (!title) return Toast.fire({ icon: 'error', title: 'يرجى إدخال اسم الدورة' });

        const btn = document.getElementById('btnStep3');
        btn.disabled = true;

        fetch("{{ route('center.onboarding.course') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ title: title, instructor_id: onboardingData.instructor_id })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                onboardingData.course_id = data.id;
                nextStep(4);
            } else { btn.disabled = false; Toast.fire({ icon: 'error', title: data.message }); }
        });
    }

    function saveStep4() {
        const name = document.getElementById('student_name').value;
        const gradeId = document.getElementById('student_grade_id').value;
        if (!name) return Toast.fire({ icon: 'error', title: 'يرجى إدخال اسم الطالب' });
        if (!gradeId) return Toast.fire({ icon: 'error', title: 'يرجى اختيار الصف' });

        const btn = document.getElementById('btnStep4');
        btn.disabled = true;

        fetch("{{ route('center.onboarding.student') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name, grade_id: gradeId })
        }).then(res => res.json()).then(data => {
            if (data.success) nextStep(5);
            else { btn.disabled = false; Toast.fire({ icon: 'error', title: data.message }); }
        });
    }

    function fetchGradeOptions() {
        const select = document.getElementById('student_grade_id');
        select.innerHTML = '<option value="">جاري التحميل...</option>';
        
        fetch("{{ route('center.onboarding.get-grades') }}")
            .then(res => res.json())
            .then(grades => {
                select.innerHTML = '<option value="">اختر الصف...</option>';
                grades.forEach(grade => {
                    const option = document.createElement('option');
                    option.value = grade.id;
                    option.textContent = (grade.stage ? grade.stage.name + ' - ' : '') + grade.name;
                    select.appendChild(option);
                });
            });
    }

    function prevStep(n) {
        nextStep(n);
    }
</script>
