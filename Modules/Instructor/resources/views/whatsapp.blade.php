@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::whatsapp.title'))
@section('page-subtitle', __('instructor::whatsapp.connect_subtitle'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-success">{{ __('instructor::whatsapp.connect_header') }}</h4>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 border-top">
                    <form action="{{ route('instructor.whatsapp.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-check form-switch mb-4 p-0 d-flex align-items-center gap-3">
                            <label class="form-check-label fw-bold cursor-pointer" for="whatsappEnabled">{{ __('instructor::whatsapp.enable_service') }}</label>
                            <input class="form-check-input ms-0" type="checkbox" name="enabled" id="whatsappEnabled" value="1" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.default_country_code') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-globe text-muted"></i></span>
                                    <input type="text" name="country_code" class="form-control bg-white focus-ring-primary" value="{{ $settings['country_code'] ?? '20' }}" placeholder="20" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.phone_number_id') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-id-card text-muted"></i></span>
                                    <input type="text" name="phone_number_id" class="form-control bg-white focus-ring-primary" value="{{ $settings['phone_number_id'] ?? '' }}" placeholder="1234567890" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.api_version') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-code-branch text-muted"></i></span>
                                    <input type="text" name="api_version" class="form-control bg-white focus-ring-primary" value="{{ $settings['api_version'] ?? 'v21.0' }}" placeholder="v21.0">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.waba_id') }} ({{ __('instructor::whatsapp.optional') }})</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-briefcase text-muted"></i></span>
                                    <input type="text" name="waba_id" class="form-control bg-white focus-ring-primary" value="{{ $settings['waba_id'] ?? '' }}" placeholder="Business ID">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.access_token') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="access_token" class="form-control bg-white focus-ring-primary" value="{{ $settings['access_token'] ?? '' }}" placeholder="EAAG..." required>
                                </div>
                                <div class="form-text small">{{ __('instructor::whatsapp.access_token_hint') }}</div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> {{ __('instructor::whatsapp.templates_header') }}</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="alert alert-info border-0 shadow-none rounded-3 py-2 px-3 mb-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="small">
                                            {{ __('instructor::whatsapp.available_variables') }} 
                                            <code class="mx-1">:student_name</code> ، 
                                            <code class="mx-1">:course_name</code> ، 
                                            <code class="mx-1">:tenant_name</code> ،
                                            <code class="mx-1">:amount</code> ،
                                            <code class="mx-1">:remaining</code>.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.attendance_msg') }}</label>
                                <textarea name="attendance_template" class="form-control bg-white rounded-3 text-start" rows="4">{{ $settings['attendance_template'] ?? '' }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.payment_msg') }}</label>
                                <textarea name="payment_template" class="form-control bg-white rounded-3 text-start" rows="4">{{ $settings['payment_template'] ?? '' }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('instructor::whatsapp.debt_msg') }}</label>
                                <textarea name="debt_template" class="form-control bg-white rounded-3 text-start" rows="4">{{ $settings['debt_template'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="alert alert-success border-0 rounded-4 bg-opacity-10 py-3 mt-4">
                            <h6 class="fw-bold fs-6"><i class="fas fa-lightbulb me-2 text-success"></i> {{ __('instructor::whatsapp.setup_steps_official') }}</h6>
                            <ul class="small mb-0 mt-2 text-secondary">
                                <li>{!! __('instructor::whatsapp.official_step_1') !!}</li>
                                <li>{{ __('instructor::whatsapp.official_step_2') }}</li>
                                <li>{!! __('instructor::whatsapp.official_step_3') !!}</li>
                                <li>{{ __('instructor::whatsapp.official_step_4') }}</li>
                            </ul>
                        </div>

                        <div class="mt-4 pt-4 border-top text-center">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm py-2 fw-bold" style="background: var(--primary-color);">
                                <i class="fas fa-save me-2"></i> {{ __('instructor::whatsapp.save_settings') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    .focus-ring-primary:focus {
        background-color: white !important;
        border: 1px solid var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(58, 12, 163, 0.1);
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
</style>
@endpush
@endsection
