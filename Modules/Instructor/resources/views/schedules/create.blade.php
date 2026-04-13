<x-instructor::layouts.master>
@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ isset($schedule) ? __('instructor::schedules.edit_title') : __('instructor::schedules.create_title') }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instructor.schedules.index') }}">{{ __('instructor::schedules.title') }}</a></li>
                <li class="breadcrumb-item active">{{ isset($schedule) ? __('instructor::sidebar.edit') : __('instructor::sidebar.add') }}</li>
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
                    <form action="{{ isset($schedule) ? route('instructor.schedules.update', $schedule) : route('instructor.schedules.store') }}" method="POST">
                        @csrf
                        @if(isset($schedule)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::schedules.group') }}</label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
                                    <option value="">{{ __('instructor::schedules.select_group') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id ?? request()->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::schedules.hall') }}</label>
                                <select name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror">
                                    <option value="">{{ __('instructor::schedules.select_hall') }}</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $schedule->classroom_id ?? '') == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }} ({{ __('instructor::groups.capacity') }}: {{ $classroom->capacity ?? '∞' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('classroom_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Hidden instructor_id: auto-set to the logged-in instructor --}}
                            <input type="hidden" name="instructor_id" value="{{ auth()->user()->instructor->id ?? '' }}">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('instructor::schedules.day') }}</label>
                                <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror">
                                    @foreach(__('instructor::schedules.days') as $value => $label)
                                        <option value="{{ $value }}" {{ old('day_of_week', $schedule->day_of_week ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('instructor::schedules.start_time') }}</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedule->start_time ?? '') }}">
                                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('instructor::schedules.end_time') }}</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedule->end_time ?? '') }}">
                                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">{{ __('instructor::groups.max_students') }}</label>
                                <input type="number" name="max_students" class="form-control @error('max_students') is-invalid @enderror" value="{{ old('max_students', $schedule->max_students ?? '') }}" placeholder="30">
                                @error('max_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 pb-5 mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold text-white shadow-sm rounded-pill border-0">{{ __('instructor::schedules.save_schedule') }}</button>
                            <a href="{{ route('instructor.schedules.index') }}" class="btn btn-light px-4 py-2 fw-bold text-muted rounded-pill">{{ __('instructor::sidebar.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background: var(--primary-gradient);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>{{ __('instructor::schedules.notes') }}</h5>
                    <ul class="list-unstyled mb-0 lh-lg">
                        <li class="mb-2 small">✓ {{ __('instructor::schedules.notes_conflict') }}</li>
                        <li class="mb-2 small">✓ {{ __('instructor::schedules.notes_vacancy') }}</li>
                        <li class="small">✓ {{ __('instructor::schedules.notes_link') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
</x-instructor::layouts.master>
