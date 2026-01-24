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
                    <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">عنوان الدورة</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg bg-light border-0">
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">المدرس</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0">
                                <option value="">اختر المدرس...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">السعر (ج.م)</label>
                                <input type="number" name="price" value="{{ old('price', 0) }}" class="form-control form-control-lg bg-light border-0" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عدد الحصص (للمبيعات)</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', 0) }}" class="form-control form-control-lg bg-light border-0" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">حالة الدورة</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i> مسودة
                                    </label>

                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status') == 'published' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i> نشر الآن
                                    </label>
                                </div>
                                <div class="form-text small text-muted mt-2">
                                    اختر "نشر الآن" لإظهار الدورة للطلاب فوراً، أو "مسودة" لإخفائها مؤقتاً.
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">صورة الغلاف</label>
                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0" accept="image/*">
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">وصف الدورة</label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>مواعيد الدورة</span>
                                <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-plus"></i> إضافة موعد
                                </button>
                            </label>
                            
                            <div id="schedules-container">
                                <!-- Dynamic Schedules will be added here -->
                            </div>
                        </div>

                        <template id="schedule-template">
                            <div class="schedule-item card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold text-primary">موعد جديد</h6>
                                        <button type="button" class="btn-close remove-schedule"></button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="small text-muted mb-1">اليوم</label>
                                            <select name="schedules[INDEX][day_of_week]" class="form-select border-0">
                                                <option value="saturday">السبت</option>
                                                <option value="sunday">الأحد</option>
                                                <option value="monday">الاثنين</option>
                                                <option value="tuesday">الثلاثاء</option>
                                                <option value="wednesday">الأربعاء</option>
                                                <option value="thursday">الخميس</option>
                                                <option value="friday">الجمعة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted mb-1">من</label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control border-0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted mb-1">إلى</label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control border-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">حفظ الدورة</button>
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
        });

        // Remove Schedule
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
            }
        });

        // Add one by default
        addButton.click();
    });
</script>
@endpush
