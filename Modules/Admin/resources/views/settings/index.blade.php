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
                    <!-- Coupons Tab -->
                    <div class="tab-pane fade" id="coupons" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">{{ __('admin.coupons_management') }}</h5>
                                <p class="text-muted small mb-0">{{ __('admin.coupons_note') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                                <i class="bi bi-plus-lg me-2"></i> {{ __('admin.new_coupon') }}
                            </button>
                        </div>

                        <!-- Coupons Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="rounded-start-3">{{ __('admin.coupon_code') }}</th>
                                        <th>{{ __('admin.coupon_name') }}</th>
                                        <th>{{ __('admin.discount_value') }}</th>
                                        <th>{{ __('admin.target_plan') }}</th>
                                        <th>{{ __('admin.valid_until') }}</th>
                                        <th>{{ __('admin.max_uses') }}</th>
                                        <th>{{ __('admin.active_status') }}</th>
                                        <th class="rounded-end-3 text-center">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons ?? [] as $coupon)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded-2 fw-bold">{{ $coupon->code }}</code>
                                        </td>
                                        <td>{{ $coupon->name ?? '-' }}</td>
                                        <td>
                                            @if($coupon->type == 'percentage')
                                                <span class="badge bg-success-subtle text-success rounded-pill">{{ $coupon->value }}%</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ number_format($coupon->value, 0) }} ر.س</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->package ? $coupon->package->name : __('admin.all_plans') }}</td>
                                        <td>
                                            @if($coupon->expires_at)
                                                @if($coupon->expires_at->isPast())
                                                    <span class="text-danger small"><i class="bi bi-x-circle me-1"></i>{{ __('admin.expired') }}</span>
                                                @else
                                                    <span class="text-muted small">{{ __('admin.valid_until') }} {{ $coupon->expires_at->format('Y-m-d') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">{{ __('admin.unlimited_uses') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill">
                                                {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->is_active && $coupon->isValid())
                                                <span class="badge bg-success rounded-pill">{{ __('admin.active_status') }}</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">{{ __('admin.inactive_status') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary rounded-start-pill" data-bs-toggle="modal" data-bs-target="#editCouponModal{{ $coupon->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger rounded-end-pill" onclick="if(confirm('{{ __('admin.delete_confirm') }}')) document.getElementById('deleteCoupon{{ $coupon->id }}').submit()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-ticket-perforated fs-1 mb-3 d-block opacity-50"></i>
                                                <p class="mb-0">{{ __('admin.no_coupons') }}</p>
                                                <small>{{ __('admin.no_coupons_hint') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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

@foreach($coupons ?? [] as $coupon)
    <!-- Delete Coupon Form (Hidden) -->
    <form id="deleteCoupon{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil me-2 text-primary"></i> تعديل الكوبون
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                                <input type="text" class="form-control rounded-3" name="code" value="{{ $coupon->code }}" style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                                <input type="text" class="form-control rounded-3" name="name" value="{{ $coupon->name }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                                <select class="form-select rounded-3" name="type">
                                    <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ر.س)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                                <input type="number" step="0.01" class="form-control rounded-3" name="value" value="{{ $coupon->value }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                                <select class="form-select rounded-3" name="package_id">
                                    <option value="">{{ __('admin.all_plans') }}</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}" {{ $coupon->package_id == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="starts_at" value="{{ $coupon->starts_at?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="expires_at" value="{{ $coupon->expires_at?->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                                <input type="number" class="form-control rounded-3" name="max_uses" value="{{ $coupon->max_uses }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="editCouponActive{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold toggle-label ms-1" for="editCouponActive{{ $coupon->id }}">{{ __('admin.coupon_active') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-lg me-1"></i> {{ __('admin.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addCouponModalLabel">
                    <i class="bi bi-ticket-perforated me-2 text-primary"></i> {{ __('admin.new_coupon') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-3" name="code" placeholder="مثال: WELCOME20" style="text-transform: uppercase;">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="this.previousElementSibling.value = 'PROMO' + Math.random().toString(36).substring(2, 8).toUpperCase()">
                                    <i class="bi bi-dice-5"></i> {{ __('admin.generate') }}
                                </button>
                            </div>
                            <small class="text-muted">{{ __('admin.coupon_code_hint') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                            <input type="text" class="form-control rounded-3" name="name" placeholder="مثال: خصم الترحيب">
                            <small class="text-muted">{{ __('admin.coupon_name_hint') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                            <select class="form-select rounded-3" name="type">
                                <option value="percentage">{{ __('admin.percentage') }}</option>
                                <option value="fixed">{{ __('admin.fixed_amount') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                            <input type="number" step="0.01" class="form-control rounded-3" name="value" placeholder="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                            <select class="form-select rounded-3" name="package_id">
                                <option value="">{{ __('admin.all_plans') }}</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="starts_at">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="expires_at">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                            <input type="number" class="form-control rounded-3" name="max_uses" placeholder="{{ __('admin.max_uses_hint') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="couponActiveSwitch" checked>
                                <label class="form-check-label fw-bold toggle-label ms-1" for="couponActiveSwitch">{{ __('admin.coupon_active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-lg me-1"></i> {{ __('admin.new_coupon') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($packages as $package)
    <form id="delete-package-{{ $package->id }}" action="{{ route('admin.settings.packages.destroy', $package->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

@foreach($features as $feature)
    <form id="delete-feature-{{ $feature->id }}" action="{{ route('admin.settings.features.destroy', $feature->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">{{ __('admin.new_package') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم (AR)</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (EN)</label>
                        <input type="text" class="form-control" name="name_en">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.slug') }}</label>
                        <input type="text" class="form-control" name="slug" placeholder="{{ __('admin.slug_placeholder') }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.price_monthly') }}</label>
                            <input type="number" step="0.01" class="form-control" name="price">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.duration_days') }}</label>
                            <input type="number" class="form-control" name="duration_in_days" value="30">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('admin.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Feature Modal -->
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-stars text-primary me-2"></i> إضافة ميزة جديدة</h5>
                    <p class="text-muted small mb-0">أدخل اسم الميزة واختر الخطط التي تريد تفعيلها فيها</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.features.store') }}" method="POST" id="addFeatureForm">
                @csrf
                <div class="modal-body px-4">
                    
                    <!-- Step 1: Basic Info -->
                    <div class="bg-light rounded-4 p-3 mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><span class="badge bg-primary rounded-circle me-2">1</span> اسم الميزة</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الاسم بالعربي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="name" id="featureNameAr" placeholder="مثال: التحضير بدون إنترنت" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الاسم بالإنجليزي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="name_en" id="featureNameEn" placeholder="e.g: Offline Attendance" required>
                                <small class="text-muted" style="font-size:0.7rem;">سيتم إنشاء الكود البرمجي تلقائياً من هذا الاسم</small>
                            </div>
                        </div>
                        <!-- Hidden auto-generated code -->
                        <input type="hidden" name="code" id="featureCodeAuto">
                    </div>

                    <!-- Step 2: Feature Type (simplified) -->
                    <div class="bg-light rounded-4 p-3 mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><span class="badge bg-primary rounded-circle me-2">2</span> نوع الميزة</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">ما هي طبيعة الميزة؟</label>
                                <div class="d-flex gap-2">
                                    <div class="form-check border rounded-3 p-3 flex-fill bg-white">
                                        <input class="form-check-input" type="radio" name="type" value="boolean" id="typeBoolean" checked>
                                        <label class="form-check-label small" for="typeBoolean">
                                            <i class="bi bi-toggle-on text-success me-1"></i> <strong>تشغيل/إيقاف</strong><br>
                                            <span class="text-muted" style="font-size:0.7rem;">مثل: البحث الذكي</span>
                                        </label>
                                    </div>
                                    <div class="form-check border rounded-3 p-3 flex-fill bg-white">
                                        <input class="form-check-input" type="radio" name="type" value="limit" id="typeLimit">
                                        <label class="form-check-label small" for="typeLimit">
                                            <i class="bi bi-sliders text-info me-1"></i> <strong>رقم محدد</strong><br>
                                            <span class="text-muted" style="font-size:0.7rem;">مثل: عدد الطلاب</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">التصنيف</label>
                                <select class="form-select rounded-3" name="category">
                                    <option value="core">⚙️ الأساسيات</option>
                                    <option value="smart" selected>✨ الميزات الذكية</option>
                                    <option value="analysis">📊 التحليلات</option>
                                    <option value="academic">📘 الأكاديمي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Assign to Plans -->
                    <div class="bg-primary bg-opacity-10 rounded-4 p-3 border border-primary border-opacity-25">
                        <h6 class="fw-bold mb-1 text-primary"><span class="badge bg-primary rounded-circle me-2">3</span> فعّلها في الخطط التالية</h6>
                        <p class="text-muted small mb-3">اختر الخطط التي سيحصل أصحابها على هذه الميزة</p>
                        <div class="row g-2">
                            @foreach($packages as $pkg)
                            <div class="col-md-6 col-lg-4">
                                <label class="form-check form-switch bg-white p-3 rounded-3 border d-flex align-items-center justify-content-between m-0 cursor-pointer h-100" for="newf_pkg_{{ $pkg->id }}">
                                    <div>
                                        <span class="fw-bold d-block">{{ $pkg->name }}</span>
                                        <small class="text-muted">{{ $pkg->price ? number_format($pkg->price) . ' ر.س' : 'مجاني' }}</small>
                                    </div>
                                    <input class="form-check-input m-0 ms-2" type="checkbox" name="assign_packages[]" value="{{ $pkg->id }}" id="newf_pkg_{{ $pkg->id }}">
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> إضافة الميزة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-generate code from English name
document.getElementById('featureNameEn')?.addEventListener('input', function() {
    const code = this.value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '_');
    document.getElementById('featureCodeAuto').value = code;
});
</script>

@foreach($features as $f)
<!-- Edit Feature Modal -->
<div class="modal fade" id="editFeatureModal{{ $f->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil text-primary me-2"></i> تعديل: {{ $f->name }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.features.update', $f->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">الاسم بالعربي</label>
                            <input type="text" class="form-control rounded-3" name="name" value="{{ $f->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">الاسم بالإنجليزي</label>
                            <input type="text" class="form-control rounded-3" name="name_en" value="{{ $f->name_en }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">التصنيف</label>
                            <select class="form-select rounded-3" name="category">
                                <option value="core" @if($f->category == 'core') selected @endif>⚙️ الأساسيات</option>
                                <option value="smart" @if($f->category == 'smart') selected @endif>✨ الميزات الذكية</option>
                                <option value="analysis" @if($f->category == 'analysis') selected @endif>📊 التحليلات</option>
                                <option value="academic" @if($f->category == 'academic') selected @endif>📘 الأكاديمي</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">الكود البرمجي</label>
                            <input type="text" class="form-control rounded-3 bg-light font-monospace" value="{{ $f->code }}" disabled>
                            <small class="text-muted" style="font-size:0.65rem;">لا يمكن تغيير الكود بعد الإنشاء</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Custom Tab Styling for Settings */
    #settingsTabs .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    #settingsTabs .nav-link.active {
        color: var(--primary-purple) !important;
        background: transparent !important;
    }

    #settingsTabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 3px 3px 0 0;
    }

    .premium-card {
        border-radius: 20px;
        border: none;
        box-shadow: var(--shadow-md);
        background: white;
        overflow: hidden;
    }

    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    /* Premium Plans Styling */
    .premium-plan-card {
        border-radius: 20px;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .premium-plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    .plan-accent-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: var(--gradient-primary);
    }

    .plan-pro .plan-accent-bar { background: linear-gradient(90deg, #3A0CA3, #4361EE); }
    .plan-basic .plan-accent-bar { background: linear-gradient(90deg, #4361EE, #5BE7C4); }
    .plan-free .plan-accent-bar { background: linear-gradient(90deg, #cbd5e1, #94a3b8); }

    .plan-pro .plan-name { color: #3A0CA3; }
    .plan-basic .plan-name { color: #4361EE; }
    
    .fw-black { font-weight: 900; }

    .premium-accordion .accordion-button {
        box-shadow: none !important;
        border-radius: 12px !important;
        transition: all 0.2s ease;
    }

    .premium-accordion .accordion-button:not(.collapsed) {
        background: rgba(42, 77, 255, 0.05) !important;
        color: var(--primary-purple) !important;
    }

    .premium-accordion .accordion-button::after {
        background-size: 1rem;
    }

    .premium-accordion .accordion-item {
        background: transparent;
    }

    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
    }

    /* Premium Switches */
    .premium-switch {
        cursor: pointer;
        width: 3.2em !important;
        height: 1.6em !important;
        background-color: #e2e8f0;
        border-color: #cbd5e1;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    .premium-switch:checked {
        background-color: #10b981 !important; /* Success Green */
        border-color: #059669 !important;
        background-position: right center;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4) !important;
    }

    .premium-switch:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
    }

    .toggle-label {
        transition: color 0.3s ease;
        color: #94a3b8;
    }

    .premium-switch:checked + .toggle-label {
        color: #10b981 !important;
        font-weight: 700 !important;
    }

    [dir="rtl"] .premium-switch:checked {
        background-position: left center;
    }

    /* RTL Switches Fix */
    [dir="rtl"] .form-switch .premium-switch {
        margin-right: -2.5em;
        margin-left: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        let tabEl;
        if (tab === 'coupons') {
            tabEl = document.querySelector('#coupons-tab');
        } else if (tab === 'features') {
            tabEl = document.querySelector('#features-tab');
        } else if (tab === 'plans') {
            tabEl = document.querySelector('#plans-tab');
        }
        
        if (tabEl) {
            const bootstrapTab = new bootstrap.Tab(tabEl);
            bootstrapTab.show();
        }
    }

    @if(isset($errors) && $errors->any())
        var addCouponModal = new bootstrap.Modal(document.getElementById('addCouponModal'));
        addCouponModal.show();
        
        // Also switch to coupon tab
        var couponTab = new bootstrap.Tab(document.querySelector('#coupons-tab'));
        couponTab.show();
    @endif
});
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
