@extends('center::layouts.app-next')

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::students.form.edit_student') }}"
        subtitle="{{ $student->name }} &bull; {{ __('center::students.form.student_code') }}: {{ $student->code }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" size="sm" icon="fas fa-arrow-right" href="{{ route('center.students.index') }}">
                {{ __('center::students.form.back_to_list') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Card 1: Student Information Form --}}
        <x-ui.card>
            <form action="{{ route('center.students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- 1. Student Info --}}
                    <div class="bg-slate-50/70 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-200/90 dark:border-slate-700/80">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-200/80 dark:border-slate-700/70">
                            <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-primary dark:bg-brand-900/40 dark:text-brand-300 flex items-center justify-center text-xs">
                                <i class="fas fa-user"></i>
                            </div>
                            <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm m-0">{{ __('center::students.form.basic_info') }}</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.full_name') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700"><i class="fas fa-user text-brand-primary"></i></span>
                                    <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="{{ __('center::students.form.name_placeholder') }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.phone_number') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700"><i class="fas fa-phone text-brand-primary"></i></span>
                                    <input type="tel" name="phone" id="phone_input" value="{{ old('phone', $student->phone) }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="{{ __('center::students.form.phone_placeholder') }}" pattern="[0-9\+\-\s\(\)]*" title="{{ __('center::students.numbers_only') }}" required>
                                </div>
                                <div id="phone-feedback" class="mt-1 small"></div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            @php
                                $displayEmail = old('email', ($student->email && !preg_match('/^std\d+\./', $student->email)) ? $student->email : '');
                            @endphp
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.email_optional') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700"><i class="fas fa-envelope text-brand-primary"></i></span>
                                    <input type="email" name="email" value="{{ $displayEmail }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="{{ __('center::students.form.email_placeholder') }}">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Parent Info --}}
                    <div class="bg-slate-50/70 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-200/90 dark:border-slate-700/80">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-200/80 dark:border-slate-700/70">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-300 flex items-center justify-center text-xs">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm m-0">{{ __('center::students.form.parent_info') }}</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.parent_name') }}</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="{{ __('center::students.form.parent_name_placeholder') }}">
                                @error('parent_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.parent_phone') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700"><i class="fas fa-users text-brand-primary"></i></span>
                                    <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="{{ __('center::students.form.parent_phone_placeholder') }}" pattern="[0-9\+\-\s\(\)]*" title="{{ __('center::students.numbers_only') }}">
                                    <span class="input-group-text bg-light border border-slate-200 {{ $student->guardian_id ? '' : 'd-none' }}" id="guardian-found-badge">
                                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill"></i> {{ __('center::students.form.guardian_found') }}</span>
                                    </span>
                                </div>
                                <div id="guardian-info-alert" class="alert alert-success border border-success/20 rounded-xl small mt-2 d-none">
                                    <i class="bi bi-info-circle-fill me-1"></i> {{ __('center::students.form.guardian_recognized', ['name' => '<span id="found-guardian-name"></span>']) }}
                                </div>
                                @error('parent_phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.parent_email') }}</label>
                                <input type="email" name="parent_email" value="{{ old('parent_email', $student->parent_email) }}" class="form-control form-control-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" placeholder="parent@email.com">
                                @error('parent_email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Academic Info --}}
                    <div class="bg-slate-50/70 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-200/90 dark:border-slate-700/80">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-200/80 dark:border-slate-700/70">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-300 flex items-center justify-center text-xs">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h5 class="font-bold text-slate-800 dark:text-slate-200 text-sm m-0">{{ __('center::students.form.academic_stage') }}</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-slate-700 dark:text-slate-300 small">{{ __('center::students.form.grade_level') }} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="grade_id" id="main_grade_select" class="form-select form-select-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 shadow-none">
                                        <option value="">{{ __('center::students.form.choose_grade') }}</option>
                                        @foreach($stages as $stage)
                                            <optgroup label="📂 {{ $stage->name }}">
                                                @foreach($stage->grades as $grade)
                                                    <option value="{{ $grade->id }}" {{ old('grade_id', $student->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                @error('grade_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="flex justify-between items-center pt-4 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('center.students.index') }}" class="btn btn-light btn-lg rounded-xl px-4 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-bold">
                            <i class="fas fa-arrow-right me-2"></i> {{ __('center::students.form.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-xl px-5 shadow-xs font-bold">
                            <i class="fas fa-check-circle me-2"></i> {{ __('center::students.form.update_student') }}
                        </button>
                    </div>
                </div>
            </form>
        </x-ui.card>

        {{-- Card 2: Enrolled Courses & Groups (Professional Display) --}}
        <x-ui.card>
            <div class="space-y-5">
                {{-- Header --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/80 dark:border-slate-700/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/40 dark:text-teal-300 flex items-center justify-center text-lg shadow-2xs">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h5 class="font-bold text-slate-900 dark:text-slate-100 text-base m-0">
                                    {{ __('center::students.profile.tabs.courses') ?? 'الكورسات والمجموعات المشترك بها' }}
                                </h5>
                                <span class="badge bg-brand-primary/10 text-brand-primary border border-brand-primary/20 rounded-full px-2.5 py-0.5 text-xs font-bold">
                                    {{ $student->enrollments->count() }}
                                </span>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 text-xs m-0 mt-0.5">
                                إدارة ومتابعة الدورات والمجموعات الدراسية المسجل بها الطالب حالياً
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="openQuickEnrollModal()" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-bold shadow-xs flex items-center gap-1.5 transition-all">
                        <i class="fas fa-plus-circle text-sm"></i>
                        <span>{{ __('center::students.enroll_in_course') ?? 'تسجيل في كورس جديد' }}</span>
                    </button>
                </div>

                {{-- Courses Grid / List --}}
                @if($student->enrollments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($student->enrollments as $enrollment)
                            @if($enrollment->course)
                                <div class="bg-white dark:bg-slate-800/80 border border-slate-200/90 dark:border-slate-700/80 rounded-2xl p-4 shadow-xs hover:border-brand-primary/50 transition-all flex flex-col justify-between">
                                    <div>
                                        {{-- Top Line: Icon, Title, Status Badge --}}
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-primary/15 to-brand-primary/5 text-brand-primary dark:bg-brand-900/40 dark:text-brand-300 flex items-center justify-center text-lg flex-shrink-0 border border-brand-primary/20">
                                                    <i class="fas fa-book-reader"></i>
                                                </div>
                                                <div>
                                                    <h6 class="font-bold text-slate-900 dark:text-slate-100 text-sm leading-snug mb-1">
                                                        {{ $enrollment->course->title }}
                                                    </h6>
                                                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                                        <span>
                                                            <i class="fas fa-chalkboard-teacher me-1 text-slate-400"></i>
                                                            {{ $enrollment->course->instructor?->name ?? 'المركز' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                @if($enrollment->status === 'active')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        نشط
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                                        {{ $enrollment->status }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Progress Bar --}}
                                        <div class="mb-3 bg-slate-50/80 dark:bg-slate-900/40 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                            <div class="flex justify-between items-center text-xs mb-1.5 font-medium">
                                                <span class="text-slate-500 dark:text-slate-400">
                                                    <i class="fas fa-chart-line text-brand-primary me-1"></i>
                                                    {{ __('center::students.profile.academic.progress') ?? 'نسبة الحضور والإنجاز' }}
                                                </span>
                                                <span class="font-bold text-brand-primary">{{ $enrollment->progress ?? 0 }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                                                <div class="bg-gradient-to-l from-brand-primary to-teal-400 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $enrollment->progress ?? 0)) }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer Info & Action Link --}}
                                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                                        <div class="text-slate-400 flex items-center gap-1">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>
                                                {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('Y-m-d') : $enrollment->created_at->format('Y-m-d') }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($enrollment->course->price > 0)
                                                <span class="font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md">
                                                    {{ number_format($enrollment->course->price, 0) }} {{ get_currency_symbol() }}
                                                </span>
                                            @endif
                                            <a href="{{ route('center.courses.show', $enrollment->course_id) }}" class="text-brand-primary hover:text-brand-dark font-bold inline-flex items-center gap-1" target="_blank">
                                                <span>عرض الكورس</span>
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-10 px-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                        <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300 flex items-center justify-center text-2xl mx-auto mb-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h6 class="font-bold text-slate-800 dark:text-slate-200 mb-1 text-sm">
                            {{ __('center::students.profile.academic.no_courses') ?? 'الطالب غير مسجل في أي كورس أو مجموعة حالياً' }}
                        </h6>
                        <p class="text-slate-500 dark:text-slate-400 text-xs mb-4 max-w-md mx-auto">
                            يمكنك تسجيل الطالب مباشرة في المجموعات أو الكورسات المتاحة لبدء الحضور والمتابعة المالية.
                        </p>
                        <button type="button" onclick="openQuickEnrollModal()" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-bold shadow-xs">
                            <i class="fas fa-plus me-1.5"></i>
                            <span>{{ __('center::students.enroll_in_course') ?? 'تسجيل في كورس الآن' }}</span>
                        </button>
                    </div>
                @endif
            </div>
        </x-ui.card>
    </div>

    {{-- Quick Course Enrollment Modal --}}
    <x-ui.modal id="quickEnrollModal" title="{{ __('center::students.enroll_in_course') }}" size="md">
        <form id="enrollForm" method="POST" onsubmit="return handleEnrollSubmit(event)">
            @csrf
            <input type="hidden" name="student_id" id="enrollStudentId" value="{{ $student->id }}">
            <div class="text-center">
                <div class="rounded-full bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 p-3 mb-3 inline-flex">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                </div>
                <h4 class="font-bold mb-1 text-slate-900 dark:text-slate-100">{{ $student->name }}</h4>
                <p class="text-slate-500 text-sm mb-4">{{ __('center::students.quick_enroll_desc_short') }}</p>

                <div class="mb-4 text-start">
                    <label class="block font-medium text-sm text-slate-700 dark:text-slate-300 mb-1.5">{{ __('center::students.available_courses') }}</label>
                    <select id="courseSelect" name="course_id" onchange="handleCourseSelectChange(this)" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm" required>
                        <option value="">-- {{ __('center::students.choose_course') }} --</option>
                        @php
                            $enrolledCourseIds = $student->enrollments->pluck('course_id')->toArray();
                        @endphp
                        @foreach($courses as $course)
                            @php
                                $isEnrolled = in_array($course->id, $enrolledCourseIds);
                            @endphp
                            <option value="{{ $course->id }}" 
                                    data-original-text="{{ $course->title }} ({{ number_format($course->price, 0) }} {{ get_currency_symbol() }})"
                                    data-enrolled="{{ $isEnrolled ? 'true' : 'false' }}"
                                    {{ $isEnrolled ? 'disabled style=color:#94a3b8;' : '' }}>
                                {{ $course->title }} ({{ number_format($course->price, 0) }} {{ get_currency_symbol() }}) {{ $isEnrolled ? ' - ' . __('center::students.already_enrolled_label') : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 p-3 bg-brand-50 dark:bg-brand-900/30 border border-brand-200 dark:border-brand-800 rounded-xl text-sm text-brand-700 dark:text-brand-300 text-start">
                    <i class="fas fa-info-circle me-1.5"></i> {{ __('center::students.auto_invoice_hint') }}
                </div>

                <div id="enrollWarning" class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl text-sm text-amber-800 dark:text-amber-200 text-start d-none">
                    <i class="fas fa-info-circle me-1.5"></i> <span id="enrollWarningText">{{ __('center::students.already_enrolled_warning') }}</span>
                </div>

                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2" @click="show = false">{{ __('center::students.close') }}</button>
                    <button type="submit" id="submitEnrollBtn" class="btn btn-primary rounded-xl px-5 py-2 fw-bold">
                        <i class="fas fa-check-circle me-1.5"></i> {{ __('center::students.complete_enrollment') }}
                    </button>
                </div>
            </div>
        </form>
    </x-ui.modal>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Phone numbers validation: digits and plus only
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "{{ __('center::students.numbers_only') }}");
                }
            });
        });

        // Name inputs validation: letters and spaces only
        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"]');
        nameInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "{{ __('center::students.letters_only') }}");
                }
            });
        });

        // Guardian lookup by phone
        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        const parentNameInput = document.querySelector('input[name="parent_name"]');

        if (parentPhoneInput) {
            let timeout = null;
            parentPhoneInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const phone = this.value.trim();

                if (phone.length >= 8) {
                    timeout = setTimeout(() => {
                        fetch(`{{ route('center.guardians.lookup') }}?phone=${phone}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.found) {
                                    if (badge) badge.classList.remove('d-none');
                                    if (alertBox) alertBox.classList.remove('d-none');
                                    if (nameSpan) nameSpan.textContent = data.guardian.name;
                                    if (parentNameInput && !parentNameInput.value) {
                                        parentNameInput.value = data.guardian.name;
                                    }
                                } else {
                                    if (badge) badge.classList.add('d-none');
                                    if (alertBox) alertBox.classList.add('d-none');
                                }
                            })
                            .catch(err => console.error('Error looking up guardian:', err));
                    }, 500);
                } else {
                    if (badge) badge.classList.add('d-none');
                    if (alertBox) alertBox.classList.add('d-none');
                }
            });
        }
    });

    // Quick Enroll Modal Handlers
    window.openQuickEnrollModal = function() {
        const selectEl = document.getElementById('courseSelect');
        if (selectEl) {
            selectEl.value = '';
        }
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quickEnrollModal' }));
    };

    window.handleCourseSelectChange = function(selectEl) {
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        const isEnrolled = selectedOpt && selectedOpt.disabled;
        const warningEl = document.getElementById('enrollWarning');
        const submitBtn = document.getElementById('submitEnrollBtn');

        if (isEnrolled) {
            if (warningEl) warningEl.classList.remove('d-none');
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (warningEl) warningEl.classList.add('d-none');
            if (submitBtn) submitBtn.disabled = false;
        }
    };

    window.handleEnrollSubmit = function(e) {
        if (e) e.preventDefault();
        const selectEl = document.getElementById('courseSelect');
        const form = document.getElementById('enrollForm');
        const courseId = selectEl ? selectEl.value : null;

        if (!courseId) {
            alert('{{ __('center::students.choose_course_first') }}');
            return false;
        }

        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        if (selectedOpt && selectedOpt.getAttribute('data-enrolled') === 'true') {
            alert('{{ __('center::students.already_enrolled_warning') }}');
            return false;
        }

        form.action = `/courses/${courseId}/enroll`;
        form.submit();
        return true;
    };
</script>
@endsection
