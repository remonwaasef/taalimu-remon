@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل الدورة: {{ $course->title }}</h2>
        <a href="{{ route('center.courses.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">عنوان الدورة</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control form-control-lg bg-light border-0">
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">المدرس</label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0">
                                <option value="">اختر المدرس...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">السعر (ج.م)</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="form-control form-control-lg bg-light border-0" min="0" step="0.01">
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عدد الحصص (للمبيعات)</label>
                                <input type="number" name="sessions_count" value="{{ old('sessions_count', $course->sessions_count) }}" class="form-control form-control-lg bg-light border-0" min="0">
                                @error('sessions_count')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">حالة الدورة</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i> مسودة
                                    </label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" {{ old('status', $course->status) == 'published' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i> نشر الآن
                                    </label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">صورة الغلاف</label>
                            @if($course->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="Current Image" class="img-thumbnail rounded" style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0" accept="image/*">
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">وصف الدورة</label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0" rows="4">{{ old('description', $course->description) }}</textarea>
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
                                @foreach($course->schedules as $index => $schedule)
                                    <div class="schedule-item card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary">موعد #{{ $index + 1 }}</h6>
                                                <button type="button" class="btn-close remove-schedule"></button>
                                            </div>
                                            <!-- Hidden ID to update existing schedules if needed, but for simple logic we might just replace them. 
                                                 However, typical simplistic update deletes old and re-creates, or updates if ID present.
                                                 For now, let's treat them as new inputs for simplicity or check controller logic.
                                                 Controller typically deletes all and creates new if we don't track IDs explicitly in the loop.
                                                 The store method was just creating. The update method is not implemented yet. 
                                                 Let's assume we will implement Update to sync() or delete/create.
                                            -->
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="small text-muted mb-1">اليوم</label>
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="form-select border-0">
                                                        @php
                                                            $days = ['sunday' => 'الأحد', 'monday' => 'الاثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة', 'saturday' => 'السبت'];
                                                            // DB stores integer 0-6 now? YES. Mapping in controller store was string->int.
                                                            // BUT data in DB is INT.
                                                            // Wait, migration defines tinyInteger.
                                                            // So $schedule->day_of_week is integer.
                                                            // We need mapped values for the select options.
                                                            // 0=Sunday, 6=Saturday.
                                                            // Wait, previously select options were strings (value="saturday").
                                                            // Controller converts String to Int.
                                                            // So here we should output Strings ("monday") as values so the Controller's existing logic works?
                                                            // Or update Controller to handle integers too.
                                                            // Let's stick to strings values to match 'create' logic if we reuse the same logic, 
                                                            // BUT 'update' logic is yet to be written. Can be smarter.
                                                            // Let's use string values for options to stay compatible with 'create' form style.
                                                        @endphp
                                                        <option value="saturday" {{ $schedule->day_of_week === 6 ? 'selected' : '' }}>السبت</option>
                                                        <option value="sunday" {{ $schedule->day_of_week === 0 ? 'selected' : '' }}>الأحد</option>
                                                        <option value="monday" {{ $schedule->day_of_week === 1 ? 'selected' : '' }}>الاثنين</option>
                                                        <option value="tuesday" {{ $schedule->day_of_week === 2 ? 'selected' : '' }}>الثلاثاء</option>
                                                        <option value="wednesday" {{ $schedule->day_of_week === 3 ? 'selected' : '' }}>الأربعاء</option>
                                                        <option value="thursday" {{ $schedule->day_of_week === 4 ? 'selected' : '' }}>الخميس</option>
                                                        <option value="friday" {{ $schedule->day_of_week === 5 ? 'selected' : '' }}>الجمعة</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted mb-1">من</label>
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="form-control border-0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted mb-1">إلى</label>
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
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">حفظ التغييرات</button>
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
        });

        // Remove Schedule
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
            }
        });
    });
</script>
@endpush
