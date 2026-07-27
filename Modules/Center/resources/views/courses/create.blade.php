@extends('center::layouts.app-next')

@section('panel-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::courses.add_new') }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::courses.cancel') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data" data-autosave="create-course">
                        @csrf

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
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg bg-white border @error('title') is-invalid border-danger @enderror">
                            @error('title')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.instructor') }}</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-white border @error('instructor_id') is-invalid border-danger @enderror">
                                <option value="">{{ __('center::courses.choose_instructor') }}</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.price') }} ({{ get_currency_symbol() }})</label>
                                <input type="number" name="price" value="{{ old('price', 0) }}" class="form-control form-control-lg bg-white border @error('price') is-invalid border-danger @enderror" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.sessions_count') }}</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', 0) }}" class="form-control form-control-lg bg-white border @error('sessions_count') is-invalid border-danger @enderror" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::courses.status_label') }}</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i>{{ __('center::courses.status_draft') }}</label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status') == 'published' ? 'checked' : '' }}>
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
                            <input type="file" name="image" class="form-control form-control-lg bg-white border @error('image') is-invalid border-danger @enderror" accept="image/*">
                            @error('image')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::courses.description') }}</label>
                            <textarea name="description" class="form-control form-control-lg bg-white border @error('description') is-invalid border-danger @enderror" rows="4">{{ old('description') }}</textarea>
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
                                <!-- Dynamic Schedules will be added here -->
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
                                            <select name="schedules[INDEX][day_of_week]" class="form-select bg-white border">
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
                                            <select name="schedules[INDEX][classroom_id]" class="form-select bg-white border">
                                                <option value="">{{ __('center::schedules.choose_classroom') }}</option>
                                                @foreach($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.from') }}</label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control bg-white border">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::schedules.to') }}</label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control bg-white border">
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
@include('center::courses.partials._create-scripts')
@endpush
