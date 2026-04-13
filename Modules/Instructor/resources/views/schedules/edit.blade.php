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
                        <i class="fas fa-calendar-alt fs-4"></i>
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
                            <label class="form-label fw-bold">{{ __('instructor::schedules.hall') }} <span class="text-muted small">({{ __('instructor::online_classes.optional') }})</span></label>
                            <select name="classroom_id" class="form-select rounded-pill px-3 @error('classroom_id') is-invalid @enderror">
                                <option value="" {{ !old('classroom_id', $schedule->classroom_id ?? '') ? 'selected' : '' }}>{{ __('instructor::schedules.select_hall') }}</option>
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
                                    <option value="{{ $value }}" {{ old('day_of_week', (string)($schedule->day_of_week ?? '')) === (string)$value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.start_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control rounded-pill px-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('instructor::schedules.end_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control rounded-pill px-3 @error('end_time') is-invalid @enderror" value="{{ old('end_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}" required>
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
                            <i class="fas fa-save me-1"></i> {{ isset($schedule) ? __('instructor::schedules.update_schedule') : __('instructor::schedules.save_schedule') }}
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
@endsection
