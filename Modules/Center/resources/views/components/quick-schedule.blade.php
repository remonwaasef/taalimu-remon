<div class="modal fade" id="quickScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="quickScheduleModalTitle">إدارة مواعيد الدورة</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="quickScheduleLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <div id="quickScheduleContent" style="display: none;">
                    <!-- Add Slot Form -->
                    <form id="quickScheduleForm" class="mb-4 bg-light p-3 rounded-4 shadow-sm">
                        @csrf
                        <input type="hidden" name="course_id" id="qs_course_id">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">اختيار الأيام</label>
                                <div class="d-flex flex-wrap gap-1" id="qs_days_container">
                                    @php
                                        $days = [0 => 'ح', 1 => 'ن', 2 => 'ث', 3 => 'ر', 4 => 'خ', 5 => 'ج', 6 => 'س'];
                                        $fullDays = [0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
                                    @endphp
                                    @foreach($fullDays as $val => $label)
                                        <div class="day-chip">
                                            <input type="checkbox" name="days[]" value="{{ $val }}" class="btn-check" id="qs_day_{{ $val }}">
                                            <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="qs_day_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">القاعة</label>
                                <select name="classroom_id" id="qs_classroom_id" class="form-select form-select-sm rounded-3" required>
                                    <option value="">اختر القاعة...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">المعلم</label>
                                <select name="instructor_id" id="qs_instructor_id" class="form-select form-select-sm rounded-3">
                                    <option value="">المعلم الافتراضي</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">من</label>
                                <input type="time" name="start_time" id="qs_start_time" class="form-control form-control-sm rounded-3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">إلى</label>
                                <input type="time" name="end_time" id="qs_end_time" class="form-control form-control-sm rounded-3" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm py-2">
                                    <i class="fas fa-plus me-1"></i> إضافة
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Existing Slots -->
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="far fa-calendar-alt me-2 text-primary"></i> المواعيد المسجلة
                    </h6>
                    <div id="qs_slots_container" class="slots-grid row g-2">
                        <!-- Slots will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.day-chip .btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    color: white;
}
.slot-item {
    transition: all 0.2s;
}
.slot-item:hover {
    background-color: #f8f9fa !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('quickScheduleModal');
    if (!modal) return;

    window.openQuickSchedule = function(courseId, courseTitle) {
        document.getElementById('quickScheduleModalTitle').innerText = 'إدارة مواعيد: ' + courseTitle;
        document.getElementById('qs_course_id').value = courseId;
        document.getElementById('quickScheduleLoading').style.display = 'block';
        document.getElementById('quickScheduleContent').style.display = 'none';
        
        const modalEl = document.getElementById('quickScheduleModal');
        // Check if bootstrap is available (Vite bundle might be loading)
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let bootstrapModal = bootstrap.Modal.getInstance(modalEl);
            if (!bootstrapModal) {
                bootstrapModal = new bootstrap.Modal(modalEl);
            }
            bootstrapModal.show();
        } else {
            // Fallback for immediate click if Vite is slow
            console.warn('Bootstrap is not loaded yet');
            alert('يتم الآن تحميل ملفات النظام، يرجى المحاولة بعد قليل...');
            return;
        }

        loadMetadata().then(() => {
            loadCourseSchedules(courseId);
        });
    };

    async function loadMetadata() {
        const classroomSelect = document.getElementById('qs_classroom_id');
        const instructorSelect = document.getElementById('qs_instructor_id');
        
        // Prevent double loading
        if (classroomSelect.options.length > 1) return;

        try {
            const response = await fetch('{{ route("center.schedules.metadata") }}');
            const data = await response.json();
            
            data.classrooms.forEach(c => {
                classroomSelect.add(new Option(c.name + ' (سعة: ' + (c.capacity || '∞') + ')', c.id));
            });

            data.instructors.forEach(i => {
                instructorSelect.add(new Option(i.name, i.id));
            });
        } catch (e) {
            console.error('Failed to load metadata', e);
        }
    }

    async function loadCourseSchedules(courseId) {
        try {
            const response = await fetch('{{ url("api/schedules/course") }}/' + courseId);
            const slots = await response.json();
            renderSlots(slots);
            
            document.getElementById('quickScheduleLoading').style.display = 'none';
            document.getElementById('quickScheduleContent').style.display = 'block';
        } catch (e) {
            console.error('Failed to load slots', e);
        }
    }

    const daysNames = {0: 'الأحد', 1: 'الاثنين', 2: 'الثلاثاء', 3: 'الأربعاء', 4: 'الخميس', 5: 'الجمعة', 6: 'السبت'};

    function renderSlots(slots) {
        const container = document.getElementById('qs_slots_container');
        container.innerHTML = '';
        
        if (slots.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted py-3">لا توجد مواعيد مسجلة حالياً</div>';
            return;
        }

        slots.forEach(slot => {
            const html = `
                <div class="col-md-6 mb-2" id="slot_${slot.id}">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-white slot-item">
                        <div>
                            <div class="fw-bold small">${daysNames[slot.day_of_week]}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                ${formatTime(slot.start_time)} - ${formatTime(slot.end_time)}
                                | <i class="fas fa-map-marker-alt text-danger ms-1"></i> ${slot.classroom ? slot.classroom.name : 'قاعة محذوفة'}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 border-0" onclick="deleteSlot(${slot.id})">
                            <i class="fas fa-trash-alt fa-sm"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    function formatTime(time) {
        // Assume time is in HH:mm:ss
        const parts = time.split(':');
        let h = parseInt(parts[0]);
        const m = parts[1];
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h > 12 ? h - 12 : h;
        h = h === 0 ? 12 : h;
        return h + ':' + m + ' ' + ampm;
    }

    window.deleteSlot = async function(id) {
        if (!confirm('هل أنت متأكد من حذف هذا الموعد؟')) return;

        try {
            const response = await fetch('{{ url("schedules") }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                document.getElementById('slot_' + id).remove();
                if (document.getElementById('qs_slots_container').children.length === 0) {
                    document.getElementById('qs_slots_container').innerHTML = '<div class="col-12 text-center text-muted py-3">لا توجد مواعيد مسجلة حالياً</div>';
                }
                // Optional: show Toast
            }
        } catch (e) {
            alert('فشل الحذف');
        }
    };

    document.getElementById('quickScheduleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        try {
            const response = await fetch('{{ route("center.schedules.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            
            if (response.ok) {
                this.reset();
                loadCourseSchedules(document.getElementById('qs_course_id').value);
            } else if (result.errors && result.errors.conflict) {
                alert(result.errors.conflict.join('\n'));
            } else {
                alert('حدث خطأ أثناء الحفظ');
            }
        } catch (e) {
            console.error(e);
            alert('خطأ في الاتصال بالسيرفر');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus me-1"></i> إضافة';
        }
    });
});
</script>
