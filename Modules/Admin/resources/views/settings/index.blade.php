@extends('admin::layouts.master')

@section('title', __('admin::admin.title'))
@section('page-title', __('admin::admin.title'))

@section('content')
<div class="container-fluid">
    <div class="premium-card">
        <div class="card-body p-0">
            <!-- Settings Tabs Navigation -->
            <div class="border-bottom px-4 pt-4">
                <ul class="nav nav-tabs border-0" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border-0 px-4 py-3 position-relative" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-gear me-2"></i> {{ __('admin::admin.general_settings') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-palette me-2"></i> {{ __('admin::admin.appearance') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-shield-lock me-2"></i> {{ __('admin::admin.security') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-card-checklist me-2"></i> {{ __('admin::admin.plans_pricing') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="features-tab" data-bs-toggle="tab" data-bs-target="#system-features" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-list-check me-2"></i> {{ __('admin::admin.system_features_tab') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="coupons-tab" data-bs-toggle="tab" data-bs-target="#coupons" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-ticket-perforated me-2"></i> {{ __('admin::admin.coupons_discounts') }}
                        </button>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                     <button type="button" class="btn btn-outline-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('admin::admin.new_package') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4" form="mainSettingsForm">
                        <i class="bi bi-check2-circle me-1"></i> {{ __('admin::admin.save_all') }}
                    </button>
                </div>
            </div>

            <!-- Settings Content -->
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="mainSettingsForm">
                @csrf
                <div class="tab-content p-4" id="settingsTabsContent">
                    <!-- General Settings -->
                    @include('admin::settings.partials._tab-general')
                    @include('admin::settings.partials._tab-appearance')
                    @include('admin::settings.partials._tab-security')
                    @include('admin::settings.partials._tab-plans')
                    @include('admin::settings.partials._tab-system-features')
                    @include('admin::settings.partials._tab-coupons')
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-white border-top p-4 d-flex justify-content-end gap-3 rounded-bottom-4">
                    <button type="reset" class="btn btn-light rounded-pill px-4">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">{{ __('admin.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin::settings.partials._coupon-modals')

@include('admin::settings.partials._package-modals')

@include('admin::settings.partials._feature-modals')

@include('admin::settings.partials._settings-styles')

@include('admin::settings.partials._settings-scripts')
@endsection
