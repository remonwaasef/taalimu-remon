@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">إضافة دورة جديدة</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data" x-data="{ showDetails: false }">
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
                        
                        <!-- Core Course Data -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">عنوان الدورة <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg bg-light border-0 @error('title') is-invalid border-danger @enderror" required>
                            @error('title')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">المدرس <span class="text-danger">*</span></label>
                                <select name="instructor_id" class="form-select form-select-lg bg-light border-0 @error('instructor_id') is-invalid border-danger @enderror" required>
                                    <option value="">اختر المدرس...</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">السعر (ج.م)</label>
                                <input type="number" name="price" value="{{ old('price', 0) }}" class="form-control form-control-lg bg-light border-0" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- Toggle Button -->
                        <div class="text-center mb-4">
                            <button type="button" @click="showDetails = !showDetails" class="btn btn-link text-decoration-none fw-bold">
                                <span x-show="!showDetails"><i class="fas fa-plus-circle me-1"></i> إضافة تفاصيل إضافية (المواعيد، الوصف، الصورة...)</span>
                                <span x-show="showDetails"><i class="fas fa-minus-circle me-1"></i> إخفاء التفاصيل الإضافية</span>
                            </button>
                        </div>

                        <!-- Advanced Details (Hidden by default) -->
                        <div x-show="showDetails" x-collapse x-cloak>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">عدد الحصص (للمبيعات)</label>
                                    <input type="number" name="sessions_count" value="{{ old('sessions_count', 0) }}" class="form-control bg-light border-0" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">حالة الدورة</label>
                                    <select name="status" class="form-select bg-light border-0">
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>نشر الآن</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">صورة الغلاف</label>
                                <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">وصف الدورة</label>
                                <textarea name="description" class="form-control bg-light border-0" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <!-- Schedule Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                    <span>مواعيد الدورة (الجدول)</span>
                                    <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-plus"></i> إضافة موعد
                                    </button>
                                </label>
                                <div id="schedules-container"></div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold">حفظ الدورة</button>
                            <a href="{{ route('center.courses.index') }}" class="btn btn-light rounded-pill py-3 mt-2">إلغاء</a>
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
        let scheduleCount = 0;

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
                        instructor_id: instructorSelect?.value || null
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

        // Add one by default
        addButton.click();
        
        // Listen for instructor changes to re-check all schedules
        instructorSelect.addEventListener('change', () => {
            document.querySelectorAll('.schedule-item').forEach(checkScheduleConflict);
        });
    });
</script>
@endpush
