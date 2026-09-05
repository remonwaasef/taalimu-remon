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

    <div class="max-w-4xl mx-auto">
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
    </div>
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
</script>
@endsection
