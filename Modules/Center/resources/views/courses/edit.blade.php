@extends('center::layouts.app-next')

@section('panel-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::courses.edit_title', ['title' => $course->title]) }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::courses.cancel') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.course_name') }}</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control form-control-lg bg-light border-0 @error('title') is-invalid border-danger @enderror">
                            @error('title')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.instructor') }}</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0 @error('instructor_id') is-invalid border-danger @enderror">
                                <option value="">{{ __('center::courses.choose_instructor') }}</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.price') }} ({{ get_currency_symbol() }})</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="form-control form-control-lg bg-light border-0 @error('price') is-invalid border-danger @enderror" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.sessions_count') }}</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', $course->sessions_count) }}" class="form-control form-control-lg bg-light border-0 @error('sessions_count') is-invalid border-danger @enderror" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.status_label') }}</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i>{{ __('center::courses.status_draft') }}</label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status', $course->status) == 'published' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i>{{ __('center::courses.status_published') }}</label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.course_image') }}</label>
                            @if($course->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($course->image) }}" alt="Current Image" class="img-thumbnail rounded" style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0 @error('image') is-invalid border-danger @enderror" accept="image/*">
                            @error('image')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.description') }}</label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0 @error('description') is-invalid border-danger @enderror" rows="4">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>{{ __('center::courses.course_schedules') }}</span>
                                <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-plus"></i>{{ __('center::courses.add_schedule') }}</button>
                            </label>
                            
                            <!-- Schedule Count Info Bar -->
                            <div id="schedule-count-info" class="alert py-2 mb-3" style="display:none;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="schedule-count-text"></span>
                            </div>
                            
                            <div id="schedules-container">
                                 @foreach($course->schedules as $index => $schedule)
                                    <div class="schedule-item card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary">{{ __('center::schedules.item_number') }} <span class="schedule-index">{{ $index + 1 }}</span></h6>
                                                <button type="button" class="btn-close remove-schedule"></button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::schedules.day') }}</label>
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="form-select border-0">
                                                        <option value="saturday" {{ $schedule->day_of_week === 6 ? 'selected' : '' }}>{{ __('center::schedules.saturday') }}</option>
                                                        <option value="sunday" {{ $schedule->day_of_week === 0 ? 'selected' : '' }}>{{ __('center::schedules.sunday') }}</option>
                                                        <option value="monday" {{ $schedule->day_of_week === 1 ? 'selected' : '' }}>{{ __('center::schedules.monday') }}</option>
                                                        <option value="tuesday" {{ $schedule->day_of_week === 2 ? 'selected' : '' }}>{{ __('center::schedules.tuesday') }}</option>
                                                        <option value="wednesday" {{ $schedule->day_of_week === 3 ? 'selected' : '' }}>{{ __('center::schedules.wednesday') }}</option>
                                                        <option value="thursday" {{ $schedule->day_of_week === 4 ? 'selected' : '' }}>{{ __('center::schedules.thursday') }}</option>
                                                        <option value="friday" {{ $schedule->day_of_week === 5 ? 'selected' : '' }}>{{ __('center::schedules.friday') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::schedules.classroom') }}</label>
                                                    <select name="schedules[{{ $index }}][classroom_id]" class="form-select border-0">
                                                        <option value="">{{ __('center::schedules.choose_classroom') }}</option>
                                                        @foreach($classrooms as $classroom)
                                                            <option value="{{ $classroom->id }}" {{ $schedule->classroom_id == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::schedules.from') }}</label>
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="form-control border-0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::schedules.to') }}</label>
                                                    <input type="time" name="schedules[{{ $index }}][end_time]" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" class="form-control border-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <template id="schedule-template">
                            <div class="schedule-item card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold text-primary">{{ __('center::schedules.item_number') }} <span class="schedule-index"></span></h6>
                                        <button type="button" class="btn-close remove-schedule"></button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.day') }}</label>
                                            <select name="schedules[INDEX][day_of_week]" class="form-select border-0">
                                                <option value="saturday">{{ __('center::schedules.saturday') }}</option>
                                                <option value="sunday">{{ __('center::schedules.sunday') }}</option>
                                                <option value="monday">{{ __('center::schedules.monday') }}</option>
                                                <option value="tuesday">{{ __('center::schedules.tuesday') }}</option>
                                                <option value="wednesday">{{ __('center::schedules.wednesday') }}</option>
                                                <option value="thursday">{{ __('center::schedules.thursday') }}</option>
                                                <option value="friday">{{ __('center::schedules.friday') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.classroom') }}</label>
                                            <select name="schedules[INDEX][classroom_id]" class="form-select border-0">
                                                <option value="">{{ __('center::schedules.choose_classroom') }}</option>
                                                @foreach($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.from') }}</label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control border-0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.to') }}</label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control border-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-lg rounded-pill shadow-sm">{{ __('center::courses.save_course') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@include('center::courses.partials._edit-scripts')
@endpush
