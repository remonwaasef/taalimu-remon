@extends('center::layouts.app-next')

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::instructors.edit_instructor_title') }}"
        subtitle="{{ $instructor->name }}"
        :breadcrumb="[
            __('center::dashboard.title') => route('center.dashboard'),
            __('center::instructors.title') => route('center.instructors.index'),
            __('center::instructors.edit_instructor_title') => null
        ]"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" icon="fas fa-arrow-right" href="{{ route('center.instructors.index') }}">
                {{ __('center::instructors.back_to_list') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

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
                                <input type="text" name="name" value="{{ old('name', $instructor->name) }}" class="form-control bg-white border" required>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.specialization') }} <span class="text-danger">*</span></label>
                                <input type="text" name="specialization" value="{{ old('specialization', $instructor->specialization) }}" class="form-control bg-white border" placeholder="{{ __('center::instructors.specialization_placeholder') }}" required>
                                @error('specialization')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Status & Administrative -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-white border" required>
                                    <option value="active" {{ old('status', $instructor->status) == 'active' ? 'selected' : '' }}>{{ __('center::instructors.active') }}</option>
                                    <option value="inactive" {{ old('status', $instructor->status) == 'inactive' ? 'selected' : '' }}>{{ __('center::instructors.inactive') }}</option>
                                    <option value="on_hold" {{ old('status', $instructor->status) == 'on_hold' ? 'selected' : '' }}>{{ __('center::instructors.on_hold') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.gender') }}</label>
                                <select name="gender" class="form-select bg-white border">
                                    <option value="">{{ __('center::instructors.select_gender') }}</option>
                                    <option value="male" {{ old('gender', $instructor->gender) == 'male' ? 'selected' : '' }}>{{ __('center::instructors.male') }}</option>
                                    <option value="female" {{ old('gender', $instructor->gender) == 'female' ? 'selected' : '' }}>{{ __('center::instructors.female') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::instructors.hiring_date') }}</label>
                                <input type="date" name="hiring_date" value="{{ old('hiring_date', $instructor->hiring_date ? $instructor->hiring_date->format('Y-m-d') : '') }}" class="form-control bg-white border">
                            </div>
                        </div>

                        <!-- Identifiers & Finance -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('center::instructors.commission_rate') }} <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                     <select name="commission_type" class="form-select bg-white border" style="max-width: 140px; border-radius: 0 10px 10px 0 !important;" required>
                                         <option value="percentage" {{ old('commission_type', $instructor->commission_type) == 'percentage' ? 'selected' : '' }}>{{ __('center::instructors.commission_percentage') }}</option>
                                         <option value="fixed" {{ old('commission_type', $instructor->commission_type) == 'fixed' ? 'selected' : '' }}>{{ __('center::instructors.commission_fixed') }}</option>
                                     </select>
                                     <input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $instructor->commission_rate) }}" class="form-control bg-white border" placeholder="0.00" style="border-radius: 10px 0 0 10px !important;" required>
                                 </div>
                                 @error('commission_rate')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                 @error('commission_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.email') }}</label>
                                <input type="email" name="email" value="{{ old('email', $instructor->email) }}" class="form-control bg-white border">
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::instructors.phone') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone', $instructor->phone) }}" class="form-control bg-white border" required>
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::instructors.bio') }}</label>
                            <textarea name="bio" class="form-control bg-white border" rows="3">{{ old('bio', $instructor->bio) }}</textarea>
                        </div>



                        <div class="d-flex flex-column gap-2 pt-2">
                            <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('center::instructors.save_changes') }}</span>
                            </button>
                            <a href="{{ route('center.instructors.index') }}" class="btn btn-outline-secondary rounded-xl py-2.5 text-center fw-bold">
                                {{ __('center::instructors.cancel') }}
                            </a>
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
                    showWarning(this, "{{ __('center::instructors.validation_numbers_only') }}");
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
                    showWarning(this, "{{ __('center::instructors.validation_letters_only') }}");
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
                    showWarning(this, "{{ __('center::instructors.validation_specialization') }}");
                }
            });
        }
    });
</script>
@endsection
