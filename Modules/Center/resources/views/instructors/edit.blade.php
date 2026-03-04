@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::messages.blade_0431') }}</h2>
        <a href="{{ route('center.instructors.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::messages.blade_0432') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $instructor->name) }}" class="form-control bg-light border-0" required>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.specialization') }} <span class="text-danger">*</span></label>
                                <input type="text" name="specialization" value="{{ old('specialization', $instructor->specialization) }}" class="form-control bg-light border-0" placeholder="{{ __('center::messages.blade_0440') }}" required>
                                @error('specialization')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Status & Administrative -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-light border-0" required>
                                    <option value="active" {{ old('status', $instructor->status) == 'active' ? 'selected' : '' }}>{{ __('center::instructors.active') }}</option>
                                    <option value="inactive" {{ old('status', $instructor->status) == 'inactive' ? 'selected' : '' }}>{{ __('center::instructors.inactive') }}</option>
                                    <option value="on_hold" {{ old('status', $instructor->status) == 'on_hold' ? 'selected' : '' }}>{{ __('center::instructors.on_hold') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.gender') }}</label>
                                <select name="gender" class="form-select bg-light border-0">
                                    <option value="">{{ __('center::messages.blade_0433') }}</option>
                                    <option value="male" {{ old('gender', $instructor->gender) == 'male' ? 'selected' : '' }}>{{ __('center::instructors.male') }}</option>
                                    <option value="female" {{ old('gender', $instructor->gender) == 'female' ? 'selected' : '' }}>{{ __('center::instructors.female') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.hiring_date') }}</label>
                                <input type="date" name="hiring_date" value="{{ old('hiring_date', $instructor->hiring_date ? $instructor->hiring_date->format('Y-m-d') : '') }}" class="form-control bg-light border-0">
                            </div>
                        </div>

                        <!-- Identifiers & Finance -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.national_id') }}</label>
                                <input type="text" name="national_id" value="{{ old('national_id', $instructor->national_id) }}" class="form-control bg-light border-0" placeholder="{{ __('center::messages.blade_0441') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.commission_rate') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="commission_type" class="form-select bg-light border-0" style="max-width: 140px; border-radius: 0 10px 10px 0 !important;" required>
                                        <option value="percentage" {{ old('commission_type', $instructor->commission_type) == 'percentage' ? 'selected' : '' }}>{{ __('center::instructors.commission_percentage') }}</option>
                                        <option value="fixed" {{ old('commission_type', $instructor->commission_type) == 'fixed' ? 'selected' : '' }}>{{ __('center::instructors.commission_fixed') }}</option>
                                    </select>
                                    <input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $instructor->commission_rate) }}" class="form-control bg-light border-0" placeholder="0.00" style="border-radius: 10px 0 0 10px !important;" required>
                                </div>
                                @error('commission_rate')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                @error('commission_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.email') }}</label>
                                <input type="email" name="email" value="{{ old('email', $instructor->email) }}" class="form-control bg-light border-0">
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.phone') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone', $instructor->phone) }}" class="form-control bg-light border-0" required>
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::instructors.bio') }}</label>
                            <textarea name="bio" class="form-control bg-light border-0" rows="3">{{ old('bio', $instructor->bio) }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0435') }}</label>
                            @if($instructor->image)
                                <div class="mb-3">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ Storage::url($instructor->image) }}" class="rounded-4 shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-light">{{ __('center::messages.blade_0436') }}</div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <small class="text-muted">{{ __('center::messages.blade_0437') }}</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold">{{ __('center::messages.blade_0438') }}</button>
                            <a href="{{ route('center.instructors.index') }}" class="btn btn-light rounded-pill py-3">{{ __('center::messages.blade_0439') }}</a>
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
                    showWarning(this, 'أرقام فقط (0-9)');
                }
            });
        });

        // Name: Letters only
        const nameInput = document.querySelector('input[name="name"]');
        if(nameInput) {
            nameInput.addEventListener('input', function() {
                let original = this.value;
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'حروف فقط (أ-ي, A-Z)');
                }
            });
        }

        // Specialization: Letters, dots, dashes
        const specInput = document.querySelector('input[name="specialization"]');
        if(specInput) {
            specInput.addEventListener('input', function() {
                let original = this.value;
                // Allow letters, spaces, dots, dashes.
                let clean = original.replace(/[0-9!@#$%^&*()_+\=\[\]{};':"\\|,<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, __('center::messages.blade_0442'));
                }
            });
        }
    });
</script>
@endsection
