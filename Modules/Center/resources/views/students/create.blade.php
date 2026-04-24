@extends('center::layouts.hope-master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::messages.blade_0719') }}</h2>
        <a href="{{ route('center.students.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::messages.blade_0721') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- 1. Student Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-person me-2"></i>{{ __('center::messages.blade_0722') }}</h5>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0724') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::messages.blade_0751') }}">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0725') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::messages.blade_0752') }}" pattern="[0-9\+\-\s\(\)]*" title="أرقام فقط (+ - مسافات)">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0726') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::messages.blade_0753') }}">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            
                            
                            
                            
                        </div>

                        <hr class="my-4">

                        {{-- 2. Parent Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-people me-2"></i>{{ __('center::messages.blade_0737') }}</h5>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0741') }}</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::messages.blade_0758') }}">
                                @error('parent_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0739') }}</label>
                                <div class="input-group">
                                    <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('center::messages.blade_0757') }}" pattern="[0-9\+\-\s\(\)]*" title="أرقام فقط (+ - مسافات)">
                                    <span class="input-group-text bg-light border-0 d-none" id="guardian-found-badge">
                                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill"></i>{{ __('center::messages.blade_0783') }}</span>
                                    </span>
                                </div>
                                <div id="guardian-info-alert" class="alert alert-success border-0 rounded-4 small mt-2 d-none">
                                    <i class="bi bi-info-circle-fill me-1"></i>{{ __('center::messages.blade_0784') }}<b><span id="found-guardian-name"></span></b>{{ __('center::messages.blade_0785') }}</div>
                                @error('parent_phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-envelope me-1 text-info opacity-50"></i> بريد ولي الأمر</label>
                                <input type="email" name="parent_email" value="{{ old('parent_email') }}" class="form-control form-control-lg bg-light border-0" placeholder="example@email.com">
                                @error('parent_email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>

                        <hr class="my-4">

                        {{-- 3. Academic Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-mortarboard me-2"></i>{{ __('center::messages.blade_0745') }}</h5>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0748') }} <span class="text-danger">*</span></label>
                                <select name="grade_id" class="form-select form-select-lg bg-light border-0">
                                    <option value="">{{ __('center::messages.blade_0747') }}</option>
                                    @foreach($stages as $stage)
                                        <optgroup label="📂 {{ $stage->name }}">
                                            @foreach($stage->grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">{{ __('center::messages.blade_0750') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function showWarning(input, msg) {
            // Remove existing warning if any
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            // Create new warning
            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            // Fade out and remove
            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

        // 1. Phone numbers: allow only digits and controls
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'أرقام فقط (0-9)');
                }
            });
        });

        // 2. Names: allow only letters and spaces (Arabic & English)
        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"]');
        nameInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                // Remove digits and special symbols
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'حروف فقط (أ-ي, A-Z)');
                }
            });
        });

        // 3. Guardian Lookup by Phone
        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        
        const parentNameInput = document.querySelector('input[name="parent_name"]');
        const addressInput = null; // Removed

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
                                badge.classList.remove('d-none');
                                alertBox.classList.remove('d-none');
                                nameSpan.textContent = data.guardian.name;
                                
                                if (!parentNameInput.value) parentNameInput.value = data.guardian.name;
                            } else {
                                badge.classList.add('d-none');
                                alertBox.classList.add('d-none');
                            }
                        })
                        .catch(err => console.error('Error looking up guardian:', err));
                }, 500);
            } else {
                badge.classList.add('d-none');
                alertBox.classList.add('d-none');
            }
        });
    });
</script>
@endsection
