@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل الدورة: {{ $course->title }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::messages.blade_0380') }}</a>
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
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0381') }}</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control form-control-lg bg-light border-0 @error('title') is-invalid border-danger @enderror">
                            @error('title')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0382') }}</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0 @error('instructor_id') is-invalid border-danger @enderror">
                                <option value="">{{ __('center::messages.blade_0383') }}</option>
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
                                <label class="form-label fw-bold">السعر (ج.م)</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="form-control form-control-lg bg-light border-0 @error('price') is-invalid border-danger @enderror" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عدد الحصص (للمبيعات)</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', $course->sessions_count) }}" class="form-control form-control-lg bg-light border-0 @error('sessions_count') is-invalid border-danger @enderror" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0384') }}</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i>{{ __('center::messages.blade_0385') }}</label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status', $course->status) == 'published' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i>{{ __('center::messages.blade_0386') }}</label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0387') }}</label>
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
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0388') }}</label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0 @error('description') is-invalid border-danger @enderror" rows="4">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>{{ __('center::messages.blade_0389') }}</span>
                                <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-plus"></i>{{ __('center::messages.blade_0390') }}</button>
                            </label>
                            
                            <div id="schedules-container">
                                @foreach($course->schedules as $index => $schedule)
                                    <div class="schedule-item card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary">موعد #{{ $index + 1 }}</h6>
                                                <button type="button" class="btn-close remove-schedule"></button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0391') }}</label>
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="form-select border-0">
                                                        <option value="saturday" {{ $schedule->day_of_week === 6 ? 'selected' : '' }}>{{ __('center::messages.blade_0392') }}</option>
                                                        <option value="sunday" {{ $schedule->day_of_week === 0 ? 'selected' : '' }}>{{ __('center::messages.blade_0393') }}</option>
                                                        <option value="monday" {{ $schedule->day_of_week === 1 ? 'selected' : '' }}>{{ __('center::messages.blade_0394') }}</option>
                                                        <option value="tuesday" {{ $schedule->day_of_week === 2 ? 'selected' : '' }}>{{ __('center::messages.blade_0395') }}</option>
                                                        <option value="wednesday" {{ $schedule->day_of_week === 3 ? 'selected' : '' }}>{{ __('center::messages.blade_0396') }}</option>
                                                        <option value="thursday" {{ $schedule->day_of_week === 4 ? 'selected' : '' }}>{{ __('center::messages.blade_0397') }}</option>
                                                        <option value="friday" {{ $schedule->day_of_week === 5 ? 'selected' : '' }}>{{ __('center::messages.blade_0398') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0399') }}</label>
                                                    <select name="schedules[{{ $index }}][classroom_id]" class="form-select border-0">
                                                        <option value="">{{ __('center::messages.blade_0400') }}</option>
                                                        @foreach($classrooms as $classroom)
                                                            <option value="{{ $classroom->id }}" {{ $schedule->classroom_id == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0401') }}</label>
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="form-control border-0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0402') }}</label>
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
                                        <h6 class="fw-bold text-primary">{{ __('center::messages.blade_0403') }}</h6>
                                        <button type="button" class="btn-close remove-schedule"></button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0404') }}</label>
                                            <select name="schedules[INDEX][day_of_week]" class="form-select border-0">
                                                <option value="saturday">{{ __('center::messages.blade_0405') }}</option>
                                                <option value="sunday">{{ __('center::messages.blade_0406') }}</option>
                                                <option value="monday">{{ __('center::messages.blade_0407') }}</option>
                                                <option value="tuesday">{{ __('center::messages.blade_0408') }}</option>
                                                <option value="wednesday">{{ __('center::messages.blade_0409') }}</option>
                                                <option value="thursday">{{ __('center::messages.blade_0410') }}</option>
                                                <option value="friday">{{ __('center::messages.blade_0411') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0412') }}</label>
                                            <select name="schedules[INDEX][classroom_id]" class="form-select border-0">
                                                <option value="">{{ __('center::messages.blade_0413') }}</option>
                                                @foreach($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0414') }}</label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control border-0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0415') }}</label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control border-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">{{ __('center::messages.blade_0416') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('schedules-container');
        const addButton = document.getElementById('add-schedule-btn');
        const template = document.getElementById('schedule-template');
        const courseId = {{ $course->id }};
        const instructorSelect = document.querySelector('select[name="instructor_id"]');
        
        // Initialize count based on existing PHP items
        let scheduleCount = {{ $course->schedules->count() }};

        // Add Schedule
        addButton.addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            const index = scheduleCount++;
            
            // Update names with index
            const inputs = clone.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', index);
            });

            container.appendChild(clone);
            
            // Attach conflict checking to new schedule item
            const newItem = container.lastElementChild;
            attachConflictChecker(newItem);
        });

        // Remove Schedule
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
            }
        });

        // Real-time Conflict Checking
        function attachConflictChecker(scheduleItem) {
            const selects = scheduleItem.querySelectorAll('select');
            const inputs = scheduleItem.querySelectorAll('input[type="time"]');
            
            [...selects, ...inputs].forEach(el => {
                el.addEventListener('change', () => checkScheduleConflict(scheduleItem));
            });
        }

        async function checkScheduleConflict(scheduleItem) {
            const daySelect = scheduleItem.querySelector('select[name*="day_of_week"]');
            const classroomSelect = scheduleItem.querySelector('select[name*="classroom_id"]');
            const startTime = scheduleItem.querySelector('input[name*="start_time"]');
            const endTime = scheduleItem.querySelector('input[name*="end_time"]');
            
            // Remove existing conflict indicator
            const existingIndicator = scheduleItem.querySelector('.conflict-indicator');
            if (existingIndicator) existingIndicator.remove();
            
            // Only check if all required fields are filled
            if (!daySelect.value || !startTime.value || !endTime.value) return;
            
            try {
                const response = await fetch('{{ route("center.schedules.check-conflict") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        day_of_week: daySelect.value,
                        start_time: startTime.value,
                        end_time: endTime.value,
                        classroom_id: classroomSelect?.value || null,
                        instructor_id: instructorSelect?.value || null,
                        exclude_course_id: courseId
                    })
                });
                
                const data = await response.json();
                
                // Create indicator element
                const indicator = document.createElement('div');
                indicator.className = 'conflict-indicator mt-2';
                
                if (data.status === 'conflict') {
                    indicator.className += ' alert alert-danger py-2';
                    indicator.innerHTML = data.conflicts.map(c => `<div>${c}</div>`).join('');
                    scheduleItem.querySelector('.card-body').appendChild(indicator);
                } else {
                    indicator.className += ' alert alert-success py-2';
                    indicator.innerHTML = '<i class="fas fa-check-circle me-1"></i> الموعد متاح';
                    scheduleItem.querySelector('.card-body').appendChild(indicator);
                    
                    // Auto-remove success message after 3 seconds
                    setTimeout(() => indicator.remove(), 3000);
                }
            } catch (error) {
                console.error('Error checking conflict:', error);
            }
        }

        // Attach conflict checker to existing schedule items
        document.querySelectorAll('.schedule-item').forEach(attachConflictChecker);
    });
</script>
@endpush
