@extends('instructor::components.layouts.master')

@section('page-title', 'إعدادات المركز')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-0">
                    <ul class="nav nav-tabs nav-justified border-bottom-0" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold border-0 rounded-0" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i> البيانات العامة
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold border-0 rounded-0" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab">
                                <i class="fab fa-whatsapp me-2"></i> إعدادات الواتساب
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="settingsTabsContent">
                        
                        {{-- Tab 1: General Settings --}}
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form action="{{ route('instructor.settings.update-general') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-3 text-center border-start">
                                        <div class="mb-3">
                                            <label class="form-label d-block fw-bold text-muted small">شعار المركز</label>
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ $tenant->logo ? asset('storage/' . $tenant->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) . '&background=3A0CA3&color=fff&size=200' }}" 
                                                     alt="Logo" class="rounded-4 shadow-sm border" style="width: 150px; height: 150px; object-fit: contain; background: #f8fafc;">
                                                <label for="logoInput" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 shadow" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*">
                                            </div>
                                            <div class="form-text x-small mt-2">يفضل استخدام صورة مربعة بحجم 512x512</div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">اسم المركز / المدرس</label>
                                                <input type="text" name="name" class="form-control bg-light border-0 rounded-3" value="{{ $tenant->name }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">رقم الهاتف العام</label>
                                                <input type="text" name="phone" class="form-control bg-light border-0 rounded-3" value="{{ $tenant->phone }}" placeholder="مثال: 01012345678">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">العنوان</label>
                                                <input type="text" name="address" class="form-control bg-light border-0 rounded-3" value="{{ $tenant->address }}" placeholder="أدخل عنوان المركز بالتفصيل">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">وصف المركز (نبذة قصيرة)</label>
                                                <textarea name="description" class="form-control bg-light border-0 rounded-3" rows="3">{{ $tenant->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات العامة
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Tab 2: WhatsApp Settings (Reused and Integrated) --}}
                        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
                            <form action="{{ route('instructor.whatsapp.update') }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-success"><i class="fab fa-whatsapp me-2"></i> ربط خدمة WhatsApp (UltraMsg)</h5>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" name="enabled" id="whatsappEnabled" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="whatsappEnabled">تفعيل الخدمة</label>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">كود الدولة الافتراضي</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-globe text-muted"></i></span>
                                            <input type="text" name="country_code" class="form-control bg-light border-0" value="{{ $settings['country_code'] ?? '20' }}" placeholder="مثال: 20">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">ID النسخة (Instance ID)</label>
                                        <input type="text" name="instance_id" class="form-control bg-light border-0" value="{{ $settings['instance_id'] ?? '' }}" placeholder="instance12345">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">الرمز السري (Token)</label>
                                        <input type="password" name="token" class="form-control bg-light border-0" value="{{ $settings['token'] ?? '' }}" placeholder="Token">
                                    </div>
                                </div>

                                <hr class="my-4 opacity-50">

                                <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> قوالب الرسائل</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">رسالة تسجيل الحضور</label>
                                        <textarea name="attendance_template" class="form-control bg-light border-0" rows="4" placeholder="خالٍ لاستخدام النص الافتراضي">{{ $settings['attendance_template'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">رسالة تأكيد الدفع</label>
                                        <textarea name="payment_template" class="form-control bg-light border-0" rows="4" placeholder="خالٍ لاستخدام النص الافتراضي">{{ $settings['payment_template'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">رسالة التذكير بالمديونية</label>
                                        <textarea name="debt_template" class="form-control bg-light border-0" rows="4" placeholder="خالٍ لاستخدام النص الافتراضي">{{ $settings['debt_template'] ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-check-circle me-2"></i> حفظ إعدادات الواتساب
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid transparent !important;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background: #f1f5f9;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        background: white !important;
        color: var(--primary-color) !important;
        border-bottom: 3px solid var(--primary-color) !important;
    }
    .form-control:focus {
        background: white !important;
        box-shadow: 0 0 0 4px rgba(58, 12, 163, 0.1);
    }
    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }
    .custom-switch .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
