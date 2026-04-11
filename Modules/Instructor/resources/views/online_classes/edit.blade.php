@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::online_classes.edit_lesson'))

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
                            <h4 class="fw-bold mb-0" style="color: var(--primary-color);">{{ __('instructor::online_classes.update_lesson') }}</h4>
                            <p class="text-muted small mb-0">{{ __('instructor::online_classes.add_online_class_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.online_classes.update', $onlineClass->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.lesson_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control rounded-pill px-3 @error('title') is-invalid @enderror" value="{{ old('title', $onlineClass->title) }}" required placeholder="{{ __('instructor::online_classes.lesson_title_placeholder') }}">
                                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.course_group') }} <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select rounded-pill px-3 @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled>{{ __('instructor::online_classes.select_group') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $onlineClass->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                @error('course_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.stream_platform') }} <span class="text-danger">*</span></label>
                                <select name="platform" class="form-select rounded-pill px-3 @error('platform') is-invalid @enderror" required>
                                    <option value="zoom" {{ old('platform', $onlineClass->platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="google_meet" {{ old('platform', $onlineClass->platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="microsoft_teams" {{ old('platform', $onlineClass->platform) == 'microsoft_teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="other" {{ old('platform', $onlineClass->platform) == 'other' ? 'selected' : '' }}>{{ __('instructor::online_classes.other') }}</option>
                                </select>
                                @error('platform') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.meeting_link') }} <span class="text-danger">*</span></label>
                                <input type="url" name="meeting_link" class="form-control rounded-pill px-3 @error('meeting_link') is-invalid @enderror" value="{{ old('meeting_link', $onlineClass->meeting_link) }}" required placeholder="{{ __('instructor::online_classes.enter_link_placeholder') }}">
                                @error('meeting_link') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.meeting_id') }} <span class="text-muted small">{{ __('instructor::online_classes.optional') }}</span></label>
                                <input type="text" name="meeting_id" class="form-control rounded-pill px-3 @error('meeting_id') is-invalid @enderror" value="{{ old('meeting_id', $onlineClass->meeting_id) }}" placeholder="123 456 789">
                                @error('meeting_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.password') }} <span class="text-muted small">{{ __('instructor::online_classes.optional') }}</span></label>
                                <input type="text" name="meeting_password" class="form-control rounded-pill px-3 @error('meeting_password') is-invalid @enderror" value="{{ old('meeting_password', $onlineClass->meeting_password) }}" placeholder="123456">
                                @error('meeting_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.start_time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_time" class="form-control rounded-pill px-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time', $onlineClass->start_time->format('Y-m-d\TH:i')) }}" required>
                                @error('start_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.duration') }} <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control rounded-pill px-3 @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $onlineClass->duration_minutes) }}" min="1" required>
                                @error('duration_minutes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('instructor::online_classes.status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select rounded-pill px-3 @error('status') is-invalid @enderror" required>
                                    <option value="scheduled" {{ old('status', $onlineClass->status) == 'scheduled' ? 'selected' : '' }}>{{ __('instructor::online_classes.scheduled') }}</option>
                                    <option value="in_progress" {{ old('status', $onlineClass->status) == 'in_progress' ? 'selected' : '' }}>{{ __('instructor::online_classes.in_progress') }}</option>
                                    <option value="completed" {{ old('status', $onlineClass->status) == 'completed' ? 'selected' : '' }}>{{ __('instructor::online_classes.completed') }}</option>
                                    <option value="canceled" {{ old('status', $onlineClass->status) == 'canceled' ? 'selected' : '' }}>{{ __('instructor::online_classes.canceled') }}</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('instructor.online_classes.index') }}" class="btn btn-light rounded-pill px-4">{{ __('instructor::online_classes.cancel') }}</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary-color);">{{ __('instructor::online_classes.update_lesson') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
