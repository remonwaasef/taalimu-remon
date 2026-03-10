@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل الدورة: {{ $course->title }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::messages.blade_0298') }}</a>
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
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0299') }}</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control form-control-lg bg-light border-0 @error('title') is-invalid border-danger @enderror">
                            @error('title')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0300') }}</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0 @error('instructor_id') is-invalid border-danger @enderror">
                                <option value="">{{ __('center::messages.blade_0301') }}</option>
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
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0302', ['currency' => get_currency_symbol()]) }}</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="form-control form-control-lg bg-light border-0 @error('price') is-invalid border-danger @enderror" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0303') }}</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', $course->sessions_count) }}" class="form-control form-control-lg bg-light border-0 @error('sessions_count') is-invalid border-danger @enderror" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('center::messages.blade_0304') }}</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i>{{ __('center::messages.blade_0305') }}</label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status', $course->status) == 'published' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i>{{ __('center::messages.blade_0306') }}</label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0307') }}</label>
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
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0308') }}</label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0 @error('description') is-invalid border-danger @enderror" rows="4">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>{{ __('center::messages.blade_0309') }}</span>
                                <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-plus"></i>{{ __('center::messages.blade_0310') }}</button>
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
                                                <h6 class="fw-bold text-primary">{{ __('center::schedules.item_number') }} {{ $index + 1 }}</h6>
                                                <button type="button" class="btn-close remove-schedule"></button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0311') }}</label>
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="form-select border-0">
                                                        <option value="saturday" {{ $schedule->day_of_week === 6 ? 'selected' : '' }}>{{ __('center::messages.blade_0312') }}</option>
                                                        <option value="sunday" {{ $schedule->day_of_week === 0 ? 'selected' : '' }}>{{ __('center::messages.blade_0313') }}</option>
                                                        <option value="monday" {{ $schedule->day_of_week === 1 ? 'selected' : '' }}>{{ __('center::messages.blade_0314') }}</option>
                                                        <option value="tuesday" {{ $schedule->day_of_week === 2 ? 'selected' : '' }}>{{ __('center::messages.blade_0315') }}</option>
                                                        <option value="wednesday" {{ $schedule->day_of_week === 3 ? 'selected' : '' }}>{{ __('center::messages.blade_0316') }}</option>
                                                        <option value="thursday" {{ $schedule->day_of_week === 4 ? 'selected' : '' }}>{{ __('center::messages.blade_0317') }}</option>
                                                        <option value="friday" {{ $schedule->day_of_week === 5 ? 'selected' : '' }}>{{ __('center::messages.blade_0318') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0319') }}</label>
                                                    <select name="schedules[{{ $index }}][classroom_id]" class="form-select border-0">
                                                        <option value="">{{ __('center::messages.blade_0320') }}</option>
                                                        @foreach($classrooms as $classroom)
                                                            <option value="{{ $classroom->id }}" {{ $schedule->classroom_id == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0321') }}</label>
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="form-control border-0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1">{{ __('center::messages.blade_0322') }}</label>
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
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0324') }}</label>
                                            <select name="schedules[INDEX][day_of_week]" class="form-select border-0">
                                                <option value="saturday">{{ __('center::messages.blade_0325') }}</option>
                                                <option value="sunday">{{ __('center::messages.blade_0326') }}</option>
                                                <option value="monday">{{ __('center::messages.blade_0327') }}</option>
                                                <option value="tuesday">{{ __('center::messages.blade_0328') }}</option>
                                                <option value="wednesday">{{ __('center::messages.blade_0329') }}</option>
                                                <option value="thursday">{{ __('center::messages.blade_0330') }}</option>
                                                <option value="friday">{{ __('center::messages.blade_0331') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0332') }}</label>
                                            <select name="schedules[INDEX][classroom_id]" class="form-select border-0">
                                                <option value="">{{ __('center::messages.blade_0333') }}</option>
                                                @foreach($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0334') }}</label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control border-0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">{{ __('center::messages.blade_0335') }}</label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control border-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-lg rounded-pill shadow-sm">{{ __('center::messages.blade_0336') }}</button>
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
        const sessionsCountInput = document.querySelector('input[name="sessions_count"]');
        const submitBtn = document.getElementById('submit-btn');
        const scheduleCountInfo = document.getElementById('schedule-count-info');
        const scheduleCountText = document.getElementById('schedule-count-text');
        
        // Initialize count based on existing PHP items
        let scheduleCount = {{ $course->schedules->count() }};

        // === Sessions-Schedules Link Functions ===
        function getRequiredSchedules() {
            return parseInt(sessionsCountInput.value) || 0;
        }

        function getCurrentScheduleCount() {
            return container.querySelectorAll('.schedule-item').length;
        }

        function updateScheduleCountUI() {
            const required = getRequiredSchedules();
            const current = getCurrentScheduleCount();

            if (required <= 0) {
                // No constraint
                scheduleCountInfo.style.display = 'none';
                addButton.style.display = '';
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
                return;
            }

            scheduleCountInfo.style.display = 'block';

            if (current < required) {
                scheduleCountInfo.className = 'alert alert-warning py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__']) }}"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = '';
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            } else if (current === required) {
                scheduleCountInfo.className = 'alert alert-success py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_complete') }}";
                addButton.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            } else {
                scheduleCountInfo.className = 'alert alert-danger py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__']) }}"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            }
        }

        // Listen for sessions_count change
        sessionsCountInput.addEventListener('input', function() {
            const required = getRequiredSchedules();
            const current = getCurrentScheduleCount();

            // Auto-add schedules if we need more
            if (required > current) {
                for (let i = current; i < required; i++) {
                    addScheduleItem();
                }
            }
            // Auto-remove extra schedules from the end
            if (required > 0 && required < current) {
                const items = container.querySelectorAll('.schedule-item');
                for (let i = items.length - 1; i >= required; i--) {
                    items[i].remove();
                }
            }

            updateScheduleCountUI();
        });

        // Add Schedule
        function addScheduleItem() {
            const clone = template.content.cloneNode(true);
            const index = scheduleCount++;
            
            // Update names with index
            const inputs = clone.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', index);
            });

            container.appendChild(clone);
            
            // Set the schedule number
            const newItem = container.lastElementChild;
            newItem.querySelector('.schedule-index').textContent = index + 1;
            
            // Attach conflict checking to new schedule item
            attachConflictChecker(newItem);
        }

        addButton.addEventListener('click', function() {
            const required = getRequiredSchedules();
            const current = getCurrentScheduleCount();

            // Don't allow adding more than required
            if (required > 0 && current >= required) {
                return;
            }

            addScheduleItem();
            updateScheduleCountUI();
        });

        // Remove Schedule
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
                updateScheduleCountUI();
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
                    indicator.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + "{{ __('center::schedules.schedule_available') }}";
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

        // Listen for instructor changes to re-check all schedules
        instructorSelect.addEventListener('change', () => {
            document.querySelectorAll('.schedule-item').forEach(checkScheduleConflict);
        });

        // Initial UI update
        updateScheduleCountUI();
    });
</script>
@endpush
