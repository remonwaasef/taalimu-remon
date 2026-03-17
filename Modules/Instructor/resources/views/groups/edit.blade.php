@extends('instructor::components.layouts.master')

@section('page-title', __('instructor::groups.group_details_edit'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0">{{ __('instructor::groups.group_details_edit') }}: {{ $course->title }}</h4>
                    <p class="text-muted small">{{ __('instructor::groups.subtitle') }}</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.groups.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_name') }}</label>
                                <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required placeholder="{{ __('instructor::groups.group_name_input_placeholder') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_description') }}</label>
                                <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="3" placeholder="{{ __('instructor::groups.group_description_placeholder') }}">{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_price_label') }}</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control rounded-start-3 py-2 @error('price') is-invalid @enderror" value="{{ old('price', $course->price) }}" required step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text rounded-end-3 bg-light border-start-0">{{ __('instructor::groups.currency') }}</span>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::groups.sessions_count') }}</label>
                                <input type="number" name="sessions_count" class="form-control rounded-3 py-2 @error('sessions_count') is-invalid @enderror" value="{{ old('sessions_count', $course->sessions_count) }}" required min="1" placeholder="{{ __('instructor::groups.sessions_placeholder') }}">
                                @error('sessions_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                             <div class="col-12 mt-5">
                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm border-0" style="background: var(--primary-color);">
                                        <i class="fas fa-save me-2"></i> {{ __('instructor::groups.update_group_btn') }}
                                    </button>
                                    <a href="{{ route('instructor.groups.list') }}" class="btn btn-light rounded-pill px-4 py-2 text-muted fw-bold">{{ __('instructor::groups.back') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
