@extends('center::layouts.hope-master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::courses.add_new') }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::courses.cancel') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('schedules-container');
        const addButton = document.getElementById('add-schedule-btn');
        const template = document.getElementById('schedule-template');
        const instructorSelect = document.querySelector('select[name="instructor_id"]');
        const sessionsCountInput = document.querySelector('input[name="sessions_count"]');
        const submitBtn = document.getElementById('submit-btn');
        const scheduleCountInfo = document.getElementById('schedule-count-info');
        const scheduleCountText = document.getElementById('schedule-count-text');
        
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
            
            // First, basic count validation
            let countValid = true;
            if (required > 0) {
                if (current !== required) countValid = false;
            }

            // Perform full validation (incompleteness + conflicts)
            const validation = validateAllSchedules();
            
            if (required <= 0) {
                scheduleCountInfo.style.display = validation.isComplete && !validation.hasConflicts ? 'none' : 'block';
                addButton.style.display = '';
            } else {
                scheduleCountInfo.style.display = 'block';
            }

            // Update UI based on results
            if (required > 0 && current < required) {
                scheduleCountInfo.className = 'alert alert-warning py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__']) }}"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = '';
            } else if (required > 0 && current > required) {
                scheduleCountInfo.className = 'alert alert-danger py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__']) }}"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = 'none';
            } else if (!validation.isComplete) {
                scheduleCountInfo.className = 'alert alert-warning py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::schedules.incomplete_schedules') }}";
                addButton.style.display = (required > 0) ? 'none' : '';
            } else if (validation.hasConflicts) {
                scheduleCountInfo.className = 'alert alert-danger py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::schedules.conflict_error') }}";
                addButton.style.display = (required > 0) ? 'none' : '';
            } else if (required > 0 && current === required) {
                scheduleCountInfo.className = 'alert alert-success py-2 mb-3';
                scheduleCountText.textContent = "{{ __('center::courses.schedules_count_complete') }}";
                addButton.style.display = 'none';
            } else {
                scheduleCountInfo.style.display = 'none';
            }

            // Final submit button control
            const canSubmit = countValid && validation.isComplete && !validation.hasConflicts;
            submitBtn.disabled = !canSubmit;
            if (canSubmit) {
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            } else {
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            }
        }

        function validateAllSchedules() {
            const items = container.querySelectorAll('.schedule-item');
            let isComplete = true;
            let hasConflicts = false;
            const data = [];

            // 1. Check for completeness and collect data
            items.forEach((item, index) => {
                const day = item.querySelector('select[name*="day_of_week"]').value;
                const classroom = item.querySelector('select[name*="classroom_id"]').value;
                const start = item.querySelector('input[name*="start_time"]').value;
                const end = item.querySelector('input[name*="end_time"]').value;

                if (!day || !start || !end) {
                    isComplete = false;
                }
                
                // Remove internal conflict warning first
                const internalWarning = item.querySelector('.internal-conflict-warning');
                if (internalWarning) internalWarning.remove();

                data.push({ item, index, day, classroom, start, end });
            });

            // 2. Check for internal conflicts (duplicate day/time/classroom)
            for (let i = 0; i < data.length; i++) {
                for (let j = i + 1; j < data.length; j++) {
                    const a = data[i];
                    const b = data[j];

                    if (a.day && a.start && a.end && 
                        a.day === b.day && 
                        ((a.start >= b.start && a.start < b.end) || (b.start >= a.start && b.start < a.end))) {
                        
                        hasConflicts = true;
                        showInternalConflict(a.item, b.index + 1);
                        showInternalConflict(b.item, a.index + 1);
                    }
                }
            }

            return { isComplete, hasConflicts: hasConflicts || !!container.querySelector('.alert-danger.conflict-indicator') };
        }

        function showInternalConflict(item, otherIndex) {
            if (item.querySelector('.internal-conflict-warning')) return;
            const warning = document.createElement('div');
            warning.className = 'internal-conflict-warning alert alert-danger py-1 mt-2 small';
            warning.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> {{ __('center::schedules.internal_conflict', ['index' => '__INDEX__']) }}`.replace('__INDEX__', otherIndex);
            item.querySelector('.card-body').appendChild(warning);
        }

        function reindexSchedules() {
            container.querySelectorAll('.schedule-item').forEach((item, index) => {
                const numberSpan = item.querySelector('.schedule-index');
                if (numberSpan) numberSpan.textContent = index + 1;
                
                const inputs = item.querySelectorAll('select, input');
                inputs.forEach(input => {
                    input.name = input.name.replace(/\[\d+\]|\[INDEX\]/g, `[${index}]`);
                });
            });
        }

        // Listen for sessions_count change
        sessionsCountInput.addEventListener('input', function() {
            const required = getRequiredSchedules();
            let current = getCurrentScheduleCount();

            if (required > current) {
                for (let i = current; i < required; i++) {
                    addScheduleItem();
                }
            }
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
            const index = getCurrentScheduleCount();
            
            const inputs = clone.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', index);
            });

            container.appendChild(clone);
            
            const newItem = container.lastElementChild;
            newItem.querySelector('.schedule-index').textContent = index + 1;
            
            attachConflictChecker(newItem);
            updateScheduleCountUI();
        }

        addButton.addEventListener('click', function() {
            if (getRequiredSchedules() > 0 && getCurrentScheduleCount() >= getRequiredSchedules()) return;
            addScheduleItem();
        });

        // Remove Schedule
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
                reindexSchedules();
                updateScheduleCountUI();
            }
        });

        // Real-time Conflict Checking
        function attachConflictChecker(scheduleItem) {
            const selects = scheduleItem.querySelectorAll('select');
            const inputs = scheduleItem.querySelectorAll('input[type="time"]');
            
            [...selects, ...inputs].forEach(el => {
                el.addEventListener('change', () => {
                    checkScheduleConflict(scheduleItem).then(() => {
                        updateScheduleCountUI();
                    });
                });
            });
        }

        async function checkScheduleConflict(scheduleItem) {
            const daySelect = scheduleItem.querySelector('select[name*="day_of_week"]');
            const classroomSelect = scheduleItem.querySelector('select[name*="classroom_id"]');
            const startTime = scheduleItem.querySelector('input[name*="start_time"]');
            const endTime = scheduleItem.querySelector('input[name*="end_time"]');
            
            const existingIndicator = scheduleItem.querySelector('.conflict-indicator');
            if (existingIndicator) existingIndicator.remove();
            
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
                        instructor_id: instructorSelect?.value || null
                    })
                });
                
                const data = await response.json();
                const indicator = document.createElement('div');
                indicator.className = 'conflict-indicator mt-2 alert py-2';
                
                if (data.status === 'conflict') {
                    indicator.classList.add('alert-danger');
                    indicator.innerHTML = data.conflicts.map(c => `<div>${c}</div>`).join('');
                } else {
                    indicator.classList.add('alert-success');
                    indicator.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + "{{ __('center::schedules.schedule_available') }}";
                    setTimeout(() => { if (indicator.parentNode) indicator.remove(); updateScheduleCountUI(); }, 3000);
                }
                scheduleItem.querySelector('.card-body').appendChild(indicator);
            } catch (error) {
                console.error('Error checking conflict:', error);
            }
        }

        // Listen for instructor changes to re-check all schedules
        instructorSelect.addEventListener('change', () => {
            document.querySelectorAll('.schedule-item').forEach(checkScheduleConflict);
        });

        // Initial UI update
        updateScheduleCountUI();
        
        // Add one by default if not constrained
        if (getRequiredSchedules() === 0 && getCurrentScheduleCount() === 0) {
            addScheduleItem();
        }
    });
</script>
@endpush
