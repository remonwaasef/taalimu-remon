@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::groups.create_new'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0">{{ __('instructor::groups.group_details_new') }}</h4>
                    <p class="text-muted small">{{ __('instructor::groups.group_hint') }}</p>
                </div>
                <div class="card-body p-4">
                    @if(session('info'))
                        <div class="alert alert-info border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-info-circle fs-5"></i>
                            <div>{{ session('info') }}</div>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-check-circle fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-exclamation-circle fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif
                    <form action="{{ route('instructor.groups.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_name_placeholder_label') }}</label>
                                <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="{{ __('instructor::groups.group_name_input_placeholder') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_description') }}</label>
                                <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="3" placeholder="{{ __('instructor::groups.group_description_placeholder') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::groups.group_price_label') }}</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control rounded-start-3 py-2 @error('price') is-invalid @enderror" value="{{ old('price') }}" required step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text rounded-end-3 bg-light border-start-0">{{ app('tenant')->settings['currency'] ?? __('instructor::groups.currency') }}</span>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('instructor::groups.sessions_count') }}</label>
                                <input type="number" name="sessions_count" class="form-control rounded-3 py-2 @error('sessions_count') is-invalid @enderror" value="{{ old('sessions_count') }}" required min="1" placeholder="{{ __('instructor::groups.sessions_placeholder') }}">
                                @error('sessions_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm border-0" style="background: var(--primary-color);">
                                        <i class="fas fa-save me-2"></i> {{ __('instructor::groups.save_group') }}
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
