@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::students.create_title'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">{{ __('instructor::students.create_student_data') }}</h4>
                    <p class="text-muted small">{{ __('instructor::students.create_student_hint') }}</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.students.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('instructor::students.student') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-user" style="color: var(--primary-color);"></i></span>
                                    <input type="text" name="name" class="form-control bg-white focus-ring-primary" placeholder="{{ __('instructor::students.name_placeholder') }}" required value="{{ old('name') }}">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::students.phone') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-phone" style="color: var(--primary-color);"></i></span>
                                    <input type="tel" name="phone" id="phone_input" class="form-control bg-white focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="{{ __('instructor::students.phone_length_error') }}" value="{{ old('phone') }}">
                                </div>
                                <div id="phone-feedback" class="mt-1 small"></div>
                                <small class="text-muted mt-1 d-block">{{ __('instructor::students.phone_hint') }}</small>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::students.email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope" style="color: var(--primary-color);"></i></span>
                                    <input type="email" name="email" class="form-control bg-white focus-ring-primary" placeholder="example@mail.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Parent Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::students.parent_phone') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-users" style="color: var(--primary-color);"></i></span>
                                    <input type="tel" name="parent_phone" class="form-control bg-white focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="{{ __('instructor::students.phone_length_error') }}" value="{{ old('parent_phone') }}">
                                </div>
                            </div>

                            <!-- Parent Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::students.parent_email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope" style="color: var(--primary-color);"></i></span>
                                    <input type="email" name="parent_email" class="form-control bg-white focus-ring-primary" placeholder="parent@mail.com" value="{{ old('parent_email') }}">
                                </div>
                            </div>

                            <!-- Course Selection -->
                            <div class="col-12">
                                <label class="form-label fw-bold mb-3">{{ __('instructor::students.target_group') }} <span class="text-muted fw-normal">({{ __('instructor::students.select_multiple_hint') ?? 'يمكنك اختيار أكثر من واحدة' }})</span></label>
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
                                        <i class="fas fa-info-circle me-1"></i> لا توجد مجموعات أو دورات متاحة حالياً.
                                    </div>
                                @endif
                                @error('course_ids')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold border-0" style="background: var(--primary-color); box-shadow: 0 4px 12px rgba(58, 12, 163, 0.2);">
                                    <i class="fas fa-user-plus me-2"></i> {{ __('instructor::students.save_and_register') }}
                                </button>
                                <a href="{{ route('instructor.students.list') }}" class="btn btn-light w-100 rounded-pill py-3 mt-2 text-muted fw-bold border-0">
                                    {{ __('instructor::students.back') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone_input');
    const feedback = document.getElementById('phone-feedback');

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            const phone = this.value;
            if (phone.length === 11) {
                feedback.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> {{ __('instructor::students.checking') }}';
                feedback.className = 'mt-1 small text-primary';

                fetch(`{{ route('instructor.students.check-phone') }}?phone=${phone}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'exists') {
                            const studentName = data.name ? data.name : '';
                            feedback.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> {{ __('instructor::students.already_registered', ['name' => '${studentName}']) }}`;
                            feedback.className = 'mt-1 small text-danger fw-bold';
                        } else if (data.status === 'available') {
                            feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> {{ __('instructor::students.phone_available') }}';
                            feedback.className = 'mt-1 small text-success fw-bold';
                        }
                    });
            } else {
                feedback.innerHTML = '';
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .focus-ring-primary:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(58, 12, 163, 0.1) !important;
        background-color: white !important;
    }

    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.3s ease; }
    .custom-checkbox-card {
        border: 1px solid transparent !important;
    }
    .custom-checkbox-card:hover { 
        transform: translateY(-3px);
        border-color: var(--primary-color) !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
        background-color: rgba(58, 12, 163, 0.02) !important;
    }
    .custom-checkbox-card:has(input:checked) {
        border: 2px solid var(--primary-color) !important;
        background-color: rgba(58, 12, 163, 0.05) !important;
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
@endpush
