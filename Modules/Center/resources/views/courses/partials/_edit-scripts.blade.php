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
            
            let countValid = true;
            if (required > 0) {
                if (current !== required) countValid = false;
            }

            const validation = validateAllSchedules();
            
            if (required <= 0) {
                scheduleCountInfo.style.display = validation.isComplete && !validation.hasConflicts ? 'none' : 'block';
                addButton.style.display = '';
            } else {
                scheduleCountInfo.style.display = 'block';
            }

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

            items.forEach((item, index) => {
                const dayField = item.querySelector('select[name*="day_of_week"]');
                const classroomField = item.querySelector('select[name*="classroom_id"]');
                const startField = item.querySelector('input[name*="start_time"]');
                const endField = item.querySelector('input[name*="end_time"]');

                const day = dayField ? dayField.value : '';
                const classroom = classroomField ? classroomField.value : '';
                const start = startField ? startField.value : '';
                const end = endField ? endField.value : '';

                if (!day || !start || !end) {
                    isComplete = false;
                }
                
                const internalWarning = item.querySelector('.internal-conflict-warning');
                if (internalWarning) internalWarning.remove();

                data.push({ item, index, day, classroom, start, end });
            });

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
            item.appendChild(warning);
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

        function addScheduleItem() {
            const clone = template.content.cloneNode(true);
            const index = getCurrentScheduleCount();
            
            const inputs = clone.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', index);
            });

            container.appendChild(clone);
            
            const newItem = container.lastElementChild;
            const indexSpan = newItem.querySelector('.schedule-index');
            if (indexSpan) indexSpan.textContent = index + 1;
            
            attachConflictChecker(newItem);
            updateScheduleCountUI();
        }

        addButton.addEventListener('click', function() {
            if (getRequiredSchedules() > 0 && getCurrentScheduleCount() >= getRequiredSchedules()) return;
            addScheduleItem();
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
                reindexSchedules();
                updateScheduleCountUI();
            }
        });

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
                        instructor_id: instructorSelect?.value || null,
                        exclude_course_id: courseId
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
                if (scheduleItem.isConnected) {
                    scheduleItem.appendChild(indicator);
                }
            } catch (error) {
                console.error('Error checking conflict:', error);
            }
        }

        document.querySelectorAll('.schedule-item').forEach(attachConflictChecker);

        instructorSelect.addEventListener('change', () => {
            document.querySelectorAll('.schedule-item').forEach(checkScheduleConflict);
        });

        // Initial UI update
        updateScheduleCountUI();
    });
</script>
