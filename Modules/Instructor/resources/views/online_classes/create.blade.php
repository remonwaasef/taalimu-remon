@extends('layouts.app-next')

@section('title', __('instructor::online_classes.add_new') ?? 'Create Online Class')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'online_classes'])
@endsection

@section('page-title', __('instructor::online_classes.add_new'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-video fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0" style="color: var(--primary-color);">{{ __('instructor::online_classes.add_online_class') }}</h4>
                            <p class="text-muted small mb-0">{{ __('instructor::online_classes.add_online_class_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.online_classes.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.lesson_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control rounded-pill px-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="{{ __('instructor::online_classes.lesson_title_placeholder') }}">
                                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.description') }}</label>
                                <textarea name="description" class="form-control rounded-4 px-3" rows="3" placeholder="{{ __('instructor::online_classes.description_placeholder') }}">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.course_group') }} <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select rounded-pill px-3 @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>{{ __('instructor::online_classes.select_group') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                @error('course_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.stream_platform') }} <span class="text-danger">*</span></label>
                                <select name="platform" id="platformSelect" class="form-select rounded-pill px-3 @error('platform') is-invalid @enderror" required>
                                    <option value="manual" {{ old('platform', 'manual') == 'manual' ? 'selected' : '' }}>
                                        Google Meet أو Zoom (رابط مباشر فائق الجودة) — موصى به ⭐
                                    </option>
                                    <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>
                                        Zoom API مدمج {{ !($zoomConfigured ?? false) ? '(يتطلب مفاتيح API في السيرفر)' : '(مدمج مع التسجيل التلقائي)' }}
                                    </option>
                                </select>
                                @error('platform') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <div class="form-text" id="platformHelp">
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i> يتم فتح قاعة الشرح بكامل الشاشة مباشرة للطلاب والمدرس مع الحضور التلقائي.</span>
                                </div>
                            </div>

                            <div class="col-md-12" id="manualLinkFields">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.meeting_link') }} <span class="text-danger">*</span></label>
                                <input type="url" name="meeting_link" class="form-control rounded-pill px-3 @error('meeting_link') is-invalid @enderror" value="{{ old('meeting_link', $defaultMeetingLink ?? '') }}" placeholder="{{ __('instructor::online_classes.enter_link_placeholder') }}">
                                @error('meeting_link') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                @if(!empty($defaultMeetingLink))
                                    <div class="form-text text-success small"><i class="fas fa-check-circle me-1"></i> تم ملء رابط البث الافتراضي المسجل في إعداداتك تلقائياً.</div>
                                @endif
                            </div>

                            <div class="col-md-12" id="zoomAutoFields" style="display:none;">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="auto_recording" id="autoRecording" {{ old('auto_recording', true) ? 'checked' : '' }} value="1">
                                    <label class="form-check-label fw-bold" for="autoRecording">{{ __('instructor::online_classes.auto_recording') }}</label>
                                </div>
                                <div class="form-text">{{ __('instructor::online_classes.auto_recording_desc') }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.access_mode') }} <span class="text-danger">*</span></label>
                                <select name="access_mode" id="accessMode" class="form-select rounded-pill px-3 @error('access_mode') is-invalid @enderror" required>
                                    <option value="course" {{ old('access_mode', 'course') == 'course' ? 'selected' : '' }}>{{ __('instructor::online_classes.access_course') }}</option>
                                    <option value="selected" {{ old('access_mode') == 'selected' ? 'selected' : '' }}>{{ __('instructor::online_classes.access_selected') }}</option>
                                </select>
                                @error('access_mode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <div class="form-text">{{ __('instructor::online_classes.access_mode_help') }}</div>
                            </div>

                            <div class="col-md-12" id="selectedStudentsContainer" style="display:none;">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.selected_students') }}</label>
                                <select name="selected_student_ids[]" id="selectedStudents" class="form-select rounded-pill px-3" multiple style="min-height: 120px;">
                                    @foreach($courses as $course)
                                        @if($course->students && $course->students->count())
                                            <optgroup label="{{ $course->title }}">
                                                @foreach($course->students as $student)
                                                    <option value="{{ $student->id }}" {{ in_array($student->id, old('selected_student_ids', [])) ? 'selected' : '' }}>{{ $student->name }} ({{ $student->code }})</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="form-text">{{ __('instructor::online_classes.selected_students_help') }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.start_time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_time" class="form-control rounded-pill px-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.duration') }} <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control rounded-pill px-3 @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', 60) }}" min="1" required>
                                @error('duration_minutes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select rounded-pill px-3 @error('status') is-invalid @enderror" required>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>{{ __('instructor::online_classes.scheduled') }}</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>{{ __('instructor::online_classes.in_progress') }}</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>{{ __('instructor::online_classes.completed') }}</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>{{ __('instructor::online_classes.canceled') }}</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('instructor.online_classes.index') }}" class="btn btn-light rounded-pill px-4">{{ __('instructor::online_classes.cancel') }}</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary-color);">{{ __('instructor::online_classes.save_and_schedule') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platformSelect');
    const manualFields = document.getElementById('manualLinkFields');
    const zoomFields = document.getElementById('zoomAutoFields');
    const accessMode = document.getElementById('accessMode');
    const studentContainer = document.getElementById('selectedStudentsContainer');

    function updatePlatformFields() {
        const inAppNotice = document.getElementById('inAppNotice');
        if (platformSelect.value === 'in_app') {
            manualFields.style.display = 'none';
            if (manualFields.querySelector('input')) manualFields.querySelector('input').required = false;
            zoomFields.style.display = 'none';
            if (inAppNotice) inAppNotice.style.display = 'inline-block';
        } else if (platformSelect.value === 'zoom') {
            manualFields.style.display = 'none';
            if (manualFields.querySelector('input')) manualFields.querySelector('input').required = false;
            zoomFields.style.display = 'block';
            if (inAppNotice) inAppNotice.style.display = 'none';
        } else {
            manualFields.style.display = 'block';
            if (manualFields.querySelector('input')) manualFields.querySelector('input').required = true;
            zoomFields.style.display = 'none';
            if (inAppNotice) inAppNotice.style.display = 'none';
        }
    }

    function updateAccessMode() {
        studentContainer.style.display = accessMode.value === 'selected' ? 'block' : 'none';
    }

    platformSelect.addEventListener('change', updatePlatformFields);
    accessMode.addEventListener('change', updateAccessMode);

    updatePlatformFields();
    updateAccessMode();

    // Initialize Select2-like multi-select if available (plain fallback)
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#selectedStudents').select2({ placeholder: 'اختر الطلاب...' });
    }
});
</script>
@endpush