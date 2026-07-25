@props([
    'actionUrl',
    'courses' => collect(),
    'stages' => collect(),
    'showGrade' => true,
    'backUrl',
    'checkPhoneUrl' => null,
    'lookupGuardianUrl' => null,
])

<form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" data-autosave="create-student">
    @csrf
    
    <!-- Wizard Navigation -->
    <ul class="nav nav-pills nav-justified mb-8 pb-4 border-b border-brand-border dark:border-slate-800 gap-3" id="studentWizard" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-xl font-bold py-3 text-xs uppercase tracking-wider transition-all" id="step1-tab" data-bs-toggle="pill" data-bs-target="#step1" type="button" role="tab" aria-controls="step1" aria-selected="true">
                <i class="fas fa-id-card me-2"></i> 1. البيانات الأساسية
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-xl font-bold py-3 text-xs uppercase tracking-wider transition-all" id="step2-tab" data-bs-toggle="pill" data-bs-target="#step2" type="button" role="tab" aria-controls="step2" aria-selected="false">
                <i class="fas fa-graduation-cap me-2"></i> 2. التسجيل والدورات
            </button>
        </li>
    </ul>

    <div class="tab-content" id="studentWizardContent">
        <!-- STEP 1: Basic Information -->
        <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
            
            {{-- 1. Student Info --}}
            <div class="row mb-4">
                <h5 class="text-secondary mb-3"><i class="bi bi-person me-2"></i>{{ __('center::students.form.basic_info') }}</h5>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.full_name') ?? __('instructor::students.student') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-user" style="color: var(--bs-primary);"></i></span>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::students.form.name_placeholder') }}" required>
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.phone_number') ?? __('instructor::students.phone') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-phone" style="color: var(--bs-primary);"></i></span>
                        <input type="tel" name="phone" id="phone_input" value="{{ old('phone') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::students.form.phone_placeholder') }}" pattern="[0-9\+\-\s\(\)]*" title="{{ __('center::students.numbers_only') }}" required>
                    </div>
                    <div id="phone-feedback" class="mt-1 small"></div>
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.email_optional') ?? __('instructor::students.email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-envelope" style="color: var(--bs-primary);"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::students.form.email_placeholder') }}">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            {{-- 2. Parent Info --}}
            <div class="row mb-4">
                <h5 class="text-secondary mb-3"><i class="bi bi-people me-2"></i>{{ __('center::students.form.parent_info') }}</h5>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.parent_name') }}</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::students.form.parent_name_placeholder') }}">
                    @error('parent_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.parent_phone') ?? __('instructor::students.parent_phone') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-users" style="color: var(--bs-primary);"></i></span>
                        <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::students.form.parent_phone_placeholder') }}" pattern="[0-9\+\-\s\(\)]*" title="{{ __('center::students.numbers_only') }}">
                        <span class="input-group-text bg-light border-0 d-none" id="guardian-found-badge">
                            <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill"></i> {{ __('center::students.form.guardian_found') }}</span>
                        </span>
                    </div>
                    <div id="guardian-info-alert" class="alert alert-success border-0 rounded-4 small mt-2 d-none">
                        <i class="bi bi-info-circle-fill me-1"></i> {{ __('center::students.form.guardian_recognized', ['name' => '<span id="found-guardian-name"></span>']) }}</div>
                    @error('parent_phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold"><i class="fas fa-envelope me-1 text-info opacity-50"></i> {{ __('center::students.parent_email') ?? __('instructor::students.parent_email') }}</label>
                    <input type="email" name="parent_email" value="{{ old('parent_email') }}" class="form-control form-control-lg bg-light border-0" placeholder="parent@email.com">
                    @error('parent_email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>

            @if($showGrade)
            <hr class="my-4">
            {{-- 3. Academic Info --}}
            <div class="row mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-secondary mb-0"><i class="bi bi-mortarboard me-2"></i>{{ __('center::students.form.academic_stage') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#gradePickerModal" id="gradePickerTrigger">
                        <i class="bi bi-grid-3x3-gap me-1"></i> {{ __('center::students.choose_from_list') }}
                    </button>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">{{ __('center::students.form.grade_level') }} <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <select name="grade_id" id="main_grade_select" class="form-select form-select-lg bg-light border-0 shadow-none">
                            <option value="">{{ __('center::students.form.choose_grade') }}</option>
                            @foreach($stages as $stage)
                                <optgroup label="📂 {{ $stage->name }}">
                                    @foreach($stage->grades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }} data-stage="{{ $stage->name }}">{{ $grade->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div id="selected-grade-chip" class="mt-2 d-none">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                <i class="bi bi-journal-check me-1"></i> <span id="chip-text"></span>
                            </span>
                        </div>
                    </div>
                    @error('grade_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm btn-next-step">
                    التالي <i class="fas fa-arrow-left ms-2"></i>
                </button>
            </div>
        </div> <!-- End Step 1 -->

        <!-- STEP 2: Courses & Confirmation -->
        <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
            
            {{-- 4. Course Enrollment --}}
            <div class="row mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-secondary mb-0"><i class="bi bi-collection-play me-2"></i>{{ __('center::students.initial_registration_optional') }}</h5>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold mb-3">{{ __('center::students.choose_groups_courses') ?? __('instructor::students.target_group') }} <span class="text-muted fw-normal">({{ __('center::students.choose_more_than_one') ?? 'يمكنك اختيار أكثر من واحدة' }})</span></label>
                    @if($courses->count() > 0)
                        <div class="row g-3">
                            @foreach($courses as $course)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check custom-checkbox-card bg-light border-0 rounded-4 p-3 h-100 d-flex align-items-center transition-all cursor-pointer" onclick="document.getElementById('course_{{ $course->id }}').click();">
                                        <input class="form-check-input ms-0 me-3" style="transform: scale(1.3);" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course_{{ $course->id }}" {{ (is_array(old('course_ids')) && in_array($course->id, old('course_ids'))) ? 'checked' : '' }} onclick="event.stopPropagation();">
                                        <label class="form-check-label w-100 cursor-pointer fw-bold text-dark m-0" for="course_{{ $course->id }}" onclick="event.stopPropagation();">
                                            {{ $course->title }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border-0 rounded-4 small text-muted">
                            <i class="bi bi-info-circle me-1"></i> لا توجد مجموعات أو دورات متاحة حالياً.
                        </div>
                    @endif
                    @error('course_ids')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                <button type="button" class="btn btn-light btn-lg rounded-pill px-4 text-secondary btn-prev-step">
                    <i class="fas fa-arrow-right me-2"></i> السابق
                </button>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> {{ __('center::students.form.save_student') ?? __('instructor::students.save_and_register') }}
                </button>
            </div>
        </div> <!-- End Step 2 -->
    </div> <!-- End Tab Content -->
</form>

@if($showGrade)
<!-- Grade Picker Modal -->
<div class="modal fade" id="gradePickerModal" tabindex="-1" aria-labelledby="gradePickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold" id="gradePickerModalLabel">{{ __('center::students.choose_from_list') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row g-3">
                    @foreach($stages as $stage)
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="text-muted fw-bold small text-uppercase letter-spacing-1 border-bottom pb-2">
                                <i class="bi bi-folder2-open me-2"></i>{{ $stage->name }}
                            </h6>
                        </div>
                        @foreach($stage->grades as $grade)
                            <div class="col-md-4 col-6">
                                <div class="grade-card p-3 rounded-4 border text-center cursor-pointer transition-all hover-shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" 
                                     onclick="selectGrade('{{ $grade->id }}', '{{ $grade->name }}', '{{ $stage->name }}')"
                                     data-grade-id="{{ $grade->id }}">
                                    <div class="grade-icon mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-book text-primary fs-5"></i>
                                    </div>
                                    <span class="fw-bold small">{{ $grade->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Wizard Navigation
        const nextBtn = document.querySelector('.btn-next-step');
        const prevBtn = document.querySelector('.btn-prev-step');
        
        if(nextBtn) {
            nextBtn.addEventListener('click', function() {
                const step2Tab = new bootstrap.Tab(document.querySelector('#step2-tab'));
                step2Tab.show();
                window.scrollTo(0, 0);
            });
        }
        
        if(prevBtn) {
            prevBtn.addEventListener('click', function() {
                const step1Tab = new bootstrap.Tab(document.querySelector('#step1-tab'));
                step1Tab.show();
                window.scrollTo(0, 0);
            });
        }

        function showWarning(input, msg) {
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "أرقام فقط");
                }
            });
        });

        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"]');
        nameInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "حروف فقط");
                }
            });
        });

        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        const parentNameInput = document.querySelector('input[name="parent_name"]');

        if(parentPhoneInput && '{{ $lookupGuardianUrl }}') {
            let timeout = null;
            parentPhoneInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const phone = this.value.trim();
                
                if (phone.length >= 8) {
                    timeout = setTimeout(() => {
                        fetch(`{{ $lookupGuardianUrl }}?phone=${phone}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.found) {
                                    if(badge) badge.classList.remove('d-none');
                                    if(alertBox) alertBox.classList.remove('d-none');
                                    if(nameSpan) nameSpan.textContent = data.guardian.name;
                                    if (parentNameInput && !parentNameInput.value) parentNameInput.value = data.guardian.name;
                                } else {
                                    if(badge) badge.classList.add('d-none');
                                    if(alertBox) alertBox.classList.add('d-none');
                                }
                            })
                            .catch(err => console.error('Error:', err));
                    }, 500);
                } else {
                    if(badge) badge.classList.add('d-none');
                    if(alertBox) alertBox.classList.add('d-none');
                }
            });
        }

        const phoneInput = document.getElementById('phone_input');
        const feedback = document.getElementById('phone-feedback');
        if (phoneInput && '{{ $checkPhoneUrl }}') {
            phoneInput.addEventListener('input', function() {
                const phone = this.value.trim();
                if (phone.length >= 10) {
                    feedback.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري التحقق...';
                    feedback.className = 'mt-1 small text-primary';

                    fetch(`{{ $checkPhoneUrl }}?phone=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'exists') {
                                const studentName = data.name ? data.name : '';
                                feedback.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> مسجل مسبقاً باسم: ${studentName}`;
                                feedback.className = 'mt-1 small text-danger fw-bold';
                            } else if (data.status === 'available') {
                                feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> الرقم متاح';
                                feedback.className = 'mt-1 small text-success fw-bold';
                            }
                        });
                } else {
                    feedback.innerHTML = '';
                }
            });
        }

        @if($showGrade)
        const mainSelect = document.getElementById('main_grade_select');
        const trigger = document.getElementById('gradePickerTrigger');
        const chip = document.getElementById('selected-grade-chip');
        const chipText = document.getElementById('chip-text');

        window.selectGrade = function(id, name, stageName) {
            mainSelect.value = id;
            updateGradeUI(id, name, stageName);
            bootstrap.Modal.getInstance(document.getElementById('gradePickerModal')).hide();
        };

        function updateGradeUI(id, name, stageName) {
            if (id) {
                trigger.classList.remove('pulse-btn', 'btn-outline-primary');
                trigger.classList.add('btn-primary', 'text-white');
                chip.classList.remove('d-none');
                chipText.textContent = `${stageName} - ${name}`;
                
                document.querySelectorAll('.grade-card').forEach(card => {
                    card.classList.toggle('active', card.dataset.gradeId == id);
                });
            } else {
                trigger.classList.add('pulse-btn', 'btn-outline-primary');
                trigger.classList.remove('btn-primary', 'text-white');
                chip.classList.add('d-none');
            }
        }

        if(mainSelect) {
            mainSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    updateGradeUI(selected.value, selected.text, selected.dataset.stage);
                } else {
                    updateGradeUI('', '', '');
                }
            });

            if (mainSelect.value) {
                const selected = mainSelect.options[mainSelect.selectedIndex];
                updateGradeUI(mainSelect.value, selected.text, selected.dataset.stage);
            } else {
                updateGradeUI('', '', '');
            }
        }
        @endif
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow-sm:hover, .custom-checkbox-card:hover { 
        transform: translateY(-3px);
        border-color: #3a0ca3 !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
        background-color: rgba(58, 12, 163, 0.02) !important;
    }
    .custom-checkbox-card:has(input:checked) {
        border: 2px solid #3a0ca3 !important;
        background-color: rgba(58, 12, 163, 0.05) !important;
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    .grade-card.active {
        border-color: #3a0ca3 !important;
        background-color: rgba(58, 12, 163, 0.05);
    }
    .grade-card.active .grade-icon {
        background-color: #3a0ca3 !important;
    }
    .grade-card.active i {
        color: white !important;
    }
    
    #gradePickerTrigger.pulse-btn {
        animation: pulse-primary 2s infinite;
    }
    
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(58, 12, 163, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(58, 12, 163, 0); }
        100% { box-shadow: 0 0 0 0 rgba(58, 12, 163, 0); }
    }
</style>
