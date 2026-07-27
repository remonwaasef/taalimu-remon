@extends('center::layouts.app-next')

@section('panel-content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ isset($schedule) ? __('center::schedules.edit_schedule') : __('center::schedules.add_new') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.schedules.index') }}">{{ __('center::schedules.schedules_list') }}</a></li>
                <li class="breadcrumb-item active">{{ isset($schedule) ? __('center::schedules.edit') : __('center::schedules.new') }}</li>
            </ol>
        </nav>
    </div>

    @if($errors->has('conflict'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first('conflict') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ isset($schedule) ? route('center.schedules.update', $schedule) : route('center.schedules.store') }}" method="POST">
                        @csrf
                        @if(isset($schedule)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::schedules.course') }}</label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
                                    <option value="">{{ __('center::schedules.choose_course') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id ?? request()->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('center::schedules.classroom') }}</label>
                                <select name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror">
                                    <option value="">{{ __('center::schedules.choose_classroom') }}</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $schedule->classroom_id ?? '') == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }} ({{ __('center::schedules.capacity') }}: {{ $classroom->capacity ?? '∞' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('classroom_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('center::schedules.instructor') }}</label>
                                <select name="instructor_id" class="form-select @error('instructor_id') is-invalid @enderror">
                                    <option value="">{{ __('center::schedules.choose_instructor') }}</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $schedule->instructor_id ?? '') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">{{ __('center::schedules.instructor_change_hint') }}</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::schedules.day') }}</label>
                                <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror">
                                    @php
                                        $days = [
                                            0 => __('center::schedules.sunday'),
                                            1 => __('center::schedules.monday'),
                                            2 => __('center::schedules.tuesday'),
                                            3 => __('center::schedules.wednesday'),
                                            4 => __('center::schedules.thursday'),
                                            5 => __('center::schedules.friday'),
                                            6 => __('center::schedules.saturday'),
                                        ];
                                    @endphp
                                    @foreach($days as $value => $label)
                                        <option value="{{ $value }}" {{ old('day_of_week', $schedule->day_of_week ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::schedules.start_time') }}</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedule->start_time ?? '') }}">
                                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::schedules.end_time') }}</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedule->end_time ?? '') }}">
                                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">{{ __('center::schedules.max_students') }}</label>
                                <input type="number" name="max_students" class="form-control @error('max_students') is-invalid @enderror" value="{{ old('max_students', $schedule->max_students ?? '') }}" placeholder="{{ __('center::schedules.max_students_placeholder') }}">
                                @error('max_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 pb-5">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">{{ __('center::schedules.save') }}</button>
                            <a href="{{ route('center.schedules.index') }}" class="btn btn-light px-4 rounded-pill">{{ __('center::schedules.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>{{ __('center::schedules.important_instructions') }}</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 small">• {{ __('center::schedules.info_1') }}</li>
                        <li class="mb-2 small">• {{ __('center::schedules.info_2') }}</li>
                        <li class="small">• {{ __('center::schedules.info_3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control {
                border-radius: 0.5rem !important;
                padding: 0.75rem 1rem !important;
                border-color: #dee2e6 !important;
            }
            .ts-control:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
            }
            .ts-dropdown {
                border-radius: 0.5rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                border: 1px solid #eee !important;
                padding: 0.5rem !important;
                z-index: 2000 !important;
            }
            .ts-dropdown .option {
                border-radius: 0.375rem !important;
                padding: 0.5rem 1rem !important;
            }
            .ts-dropdown .active {
                background-color: var(--primary-color, #3A0CA3) !important;
                color: #fff !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.querySelectorAll('select').forEach((el) => {
                new TomSelect(el, {
                    plugins: ['dropdown_input'],
                    dropdownParent: 'body',
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    render:{
                        no_results:function(data,escape){
                            return '<div class="no-results p-2 text-muted">{{ __('center::schedules.no_results') }}</div>';
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
