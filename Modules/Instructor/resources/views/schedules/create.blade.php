@extends('instructor::components.layouts.hope-master')

@section('page-title', isset($schedule) ? __('instructor::schedules.edit_title') : __('instructor::schedules.create_title'))
@section('page-subtitle', __('instructor::schedules.subtitle'))

@section('page-actions')
    <a href="{{ route('instructor.schedules.index') }}" class="btn btn-glass">
        <i class="fas fa-arrow-left me-1"></i> {{ __('instructor::sidebar.back') ?? __('instructor::groups.back') }}
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        @if($errors->has('conflict'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $errors->first('conflict') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-calendar-plus fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="color: var(--primary-color);">{{ isset($schedule) ? __('instructor::schedules.update_schedule') : __('instructor::schedules.add_schedule') }}</h4>
                        <p class="text-muted small mb-0">{{ __('instructor::schedules.subtitle') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($schedule) ? route('instructor.schedules.update', $schedule) : route('instructor.schedules.store') }}" method="POST">
                    @csrf
                    @if(isset($schedule)) @method('PUT') @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.group') }} <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select rounded-pill px-3 @error('course_id') is-invalid @enderror" required>
                                <option value="" disabled selected>{{ __('instructor::schedules.select_group') }}</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id ?? request()->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">{{ __('instructor::schedules.hall') }} <span class="text-muted small">({{ __('instructor::online_classes.optional') }})</span></label>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#addClassroomModal">
                                    <i class="fas fa-plus-circle me-1"></i>{{ __('instructor::schedules.add_hall') ?? 'إضافة قاعة' }}
                                </button>
                            </div>
                            <select name="classroom_id" id="classroom_id" class="form-select rounded-pill px-3 @error('classroom_id') is-invalid @enderror">
                                <option value="" selected>{{ __('instructor::schedules.select_hall') }}</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $schedule->classroom_id ?? '') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }} ({{ __('instructor::schedules.capacity') }}: {{ $classroom->capacity ?? '∞' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('classroom_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.location') }} <span class="text-muted small">({{ __('instructor::online_classes.optional') }})</span></label>
                            <input type="text" name="location" class="form-control rounded-pill px-3 @error('location') is-invalid @enderror" value="{{ old('location', $schedule->location ?? '') }}" placeholder="{{ __('instructor::schedules.location_placeholder') }}">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Hidden instructor_id: auto-set to the logged-in instructor --}}
                        <input type="hidden" name="instructor_id" value="{{ auth()->user()->instructor->id ?? '' }}">

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.day') }} <span class="text-danger">*</span></label>
                            <select name="day_of_week" class="form-select rounded-pill px-3 @error('day_of_week') is-invalid @enderror" required>
                                <option value="" disabled selected>{{ __('instructor::schedules.select_day') }}</option>
                                @foreach(__('instructor::schedules.days') as $value => $label)
                                    <option value="{{ $value }}" {{ old('day_of_week', $schedule->day_of_week ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.start_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control rounded-pill px-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedule->start_time ?? '') }}" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.end_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control rounded-pill px-3 @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedule->end_time ?? '') }}" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">{{ __('instructor::groups.max_students') }} <span class="text-muted small">({{ __('instructor::online_classes.optional') }})</span></label>
                            <input type="number" name="max_students" class="form-control rounded-pill px-3 @error('max_students') is-invalid @enderror" value="{{ old('max_students', $schedule->max_students ?? '') }}" placeholder="30">
                            @error('max_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-flex justify-content-end gap-2 mt-4 pb-2">
                        <a href="{{ route('instructor.schedules.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">{{ __('instructor::sidebar.cancel') }}</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold text-white shadow-sm border-0" style="background: var(--primary-color);">
                            <i class="fas fa-save me-1"></i> {{ __('instructor::schedules.save_schedule') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header border-0 pb-0 pt-4 bg-white">
                <h5 class="fw-bold mb-0" style="color: var(--primary-color);">
                    <i class="fas fa-info-circle me-2"></i> {{ __('instructor::schedules.notes') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-check small"></i>
                        </div>
                        <p class="mb-0 small text-muted">{{ __('instructor::schedules.notes_conflict') }}</p>
                    </div>
                    
                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-user-clock small"></i>
                        </div>
                        <p class="mb-0 small text-muted">{{ __('instructor::schedules.notes_vacancy') }}</p>
                    </div>

                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-sync-alt small"></i>
                        </div>
                        <p class="mb-0 small text-muted">{{ __('instructor::schedules.notes_link') }}</p>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-4 text-white position-relative overflow-hidden" style="background: var(--primary-gradient);">
                    <i class="fas fa-calendar-alt position-absolute end-0 bottom-0 mb-n4 me-n2 opacity-25" style="font-size: 6rem;"></i>
                    <h6 class="fw-bold mb-2">{{ __('instructor::sidebar.instructor') }}</h6>
                    <p class="mb-0 small opacity-75">{{ __('instructor::sidebar.panel_title') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('modals')
<div class="modal fade" id="addClassroomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">{{ __('instructor::schedules.add_hall') ?? 'إضافة قاعة جديدة' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('instructor::schedules.hall_name') ?? 'اسم القاعة' }}</label>
                    <input type="text" id="new_classroom_name" class="form-control rounded-pill" placeholder="مثلاً: قاعة 101">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('instructor::schedules.capacity') ?? 'السعة' }}</label>
                    <input type="number" id="new_classroom_capacity" class="form-control rounded-pill" placeholder="30">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" id="saveClassroomBtn" class="btn btn-primary w-100 rounded-pill border-0" style="background: var(--primary-color);">{{ __('instructor::sidebar.save') ?? 'حفظ' }}</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveClassroomBtn');
    const classroomSelect = document.getElementById('classroom_id');
    const modal = new bootstrap.Modal(document.getElementById('addClassroomModal'));

    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const name = document.getElementById('new_classroom_name').value;
            const capacity = document.getElementById('new_classroom_capacity').value;

            if (!name) {
                alert('يرجى إدخال اسم القاعة');
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جارٍ الحفظ...';

            fetch('{{ route('instructor.classrooms.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, capacity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update select options
                    classroomSelect.innerHTML = '<option value="">{{ __('instructor::schedules.select_hall') }}</option>';
                    data.classrooms.forEach(c => {
                        const option = document.createElement('option');
                        option.value = c.id;
                        option.textContent = `${c.name} (${c.capacity || '∞'})`;
                        if (c.id == data.new_id) option.selected = true;
                        classroomSelect.appendChild(option);
                    });
                    
                    modal.hide();
                    document.getElementById('new_classroom_name').value = '';
                    document.getElementById('new_classroom_capacity').value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ القاعة');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '{{ __('instructor::sidebar.save') ?? 'حفظ' }}';
            });
        });
    }
});
</script>
@endpush
@endsection
