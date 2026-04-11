@extends('instructor::components.layouts.hope-master')

@section('page-title', 'تعديل درس أونلاين')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-edit fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0" style="color: var(--primary-color);">تعديل الدرس: {{ $onlineClass->title }}</h4>
                            <p class="text-muted small mb-0">تحديث تفاصيل وروابط الدرس الأونلاين</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.online_classes.update', $onlineClass->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">عنوان الدرس <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control rounded-pill px-3 @error('title') is-invalid @enderror" value="{{ old('title', $onlineClass->title) }}" required>
                                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">المجموعة الدراسية (الكورس) <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select rounded-pill px-3 @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled>-- اختر المجموعة --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $onlineClass->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                @error('course_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">منصة البث <span class="text-danger">*</span></label>
                                <select name="platform" class="form-select rounded-pill px-3 @error('platform') is-invalid @enderror" required>
                                    <option value="zoom" {{ old('platform', $onlineClass->platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="google_meet" {{ old('platform', $onlineClass->platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="microsoft_teams" {{ old('platform', $onlineClass->platform) == 'microsoft_teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="other" {{ old('platform', $onlineClass->platform) == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('platform') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">رابط الدخول (Meeting Link) <span class="text-danger">*</span></label>
                                <input type="url" name="meeting_link" class="form-control rounded-pill px-3 @error('meeting_link') is-invalid @enderror" value="{{ old('meeting_link', $onlineClass->meeting_link) }}" required>
                                @error('meeting_link') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">معرف الجلسة (Meeting ID) <span class="text-muted small">(اختياري)</span></label>
                                <input type="text" name="meeting_id" class="form-control rounded-pill px-3 @error('meeting_id') is-invalid @enderror" value="{{ old('meeting_id', $onlineClass->meeting_id) }}">
                                @error('meeting_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">كلمة المرور (Password) <span class="text-muted small">(اختياري)</span></label>
                                <input type="text" name="meeting_password" class="form-control rounded-pill px-3 @error('meeting_password') is-invalid @enderror" value="{{ old('meeting_password', $onlineClass->meeting_password) }}">
                                @error('meeting_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">تاريخ ووقت البدء <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_time" class="form-control rounded-pill px-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time', $onlineClass->start_time->format('Y-m-d\TH:i')) }}" required>
                                @error('start_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">المدة (بالدقائق) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control rounded-pill px-3 @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $onlineClass->duration_minutes) }}" min="1" required>
                                @error('duration_minutes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">الحالة <span class="text-danger">*</span></label>
                                <select name="status" class="form-select rounded-pill px-3 @error('status') is-invalid @enderror" required>
                                    <option value="scheduled" {{ old('status', $onlineClass->status) == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                                    <option value="in_progress" {{ old('status', $onlineClass->status) == 'in_progress' ? 'selected' : '' }}>قيد الانعقاد</option>
                                    <option value="completed" {{ old('status', $onlineClass->status) == 'completed' ? 'selected' : '' }}>منتهي</option>
                                    <option value="cancelled" {{ old('status', $onlineClass->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('instructor.online_classes.index') }}" class="btn btn-light rounded-pill px-4">إلغاء</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary-color);">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
