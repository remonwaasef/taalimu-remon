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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold border-0 rounded-0" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription" type="button" role="tab">
                                <i class="fas fa-credit-card me-2"></i> اشتراك المنصة
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

                        {{-- Tab 3: Subscription Information --}}
                        <div class="tab-pane fade" id="subscription" role="tabpanel">
                            @php
                                $subscription = $tenant->activeSubscription();
                                $package = $subscription ? $subscription->resolved_package : null;
                                $service = app(\App\Services\SubscriptionService::class);
                            @endphp

                            @if($subscription && $package)
                                <div class="row g-4">
                                    <div class="col-md-5">
                                        <div class="card bg-primary text-white border-0 rounded-4 shadow-sm h-100 position-relative overflow-hidden">
                                            <div class="card-body p-4 position-relative z-1">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold">الخطة الحالية</span>
                                                    @if($subscription->ends_at)
                                                        <small class="opacity-75">تنتهي في: {{ $subscription->ends_at->format('Y/m/d') }}</small>
                                                    @endif
                                                </div>
                                                <h2 class="fw-bold mb-1">{{ $package->name }}</h2>
                                                <p class="opacity-75 small mb-4">{{ $package->description }}</p>
                                                
                                                <div class="d-flex align-items-center gap-3 mt-auto">
                                                    @if($subscription->status === 'active')
                                                        <span class="badge bg-success border border-white border-opacity-25 py-2 px-3 rounded-pill"><i class="fas fa-check-circle me-1"></i> اشتراك نشط</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark py-2 px-3 rounded-pill">بحاجة للتجديد</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fas fa-crown position-absolute bottom-0 end-0 opacity-10 m-n3" style="font-size: 150px;"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2 text-primary"></i> إحصائيات الاستهلاك</h6>
                                        <div class="row g-3">
                                            @php
                                                $features = [
                                                    ['code' => 'max_students', 'label' => 'الطلاب', 'icon' => 'fa-user-graduate'],
                                                    ['code' => 'max_courses', 'label' => 'المجموعات', 'icon' => 'fa-users'],
                                                    ['code' => 'max_instructors', 'label' => 'المساعدين', 'icon' => 'fa-chalkboard-teacher'],
                                                ];
                                            @endphp

                                            @foreach($features as $f)
                                                @php
                                                    $limit = $service->getFeatureValue($tenant, $f['code']);
                                                    $usage = 0;
                                                    if($f['code'] == 'max_students') $usage = $tenant->users()->where('role', 'student')->count();
                                                    if($f['code'] == 'max_courses') $usage = \App\Models\Course::where('tenant_id', $tenant->id)->count();
                                                    if($f['code'] == 'max_instructors') $usage = \App\Models\Instructor::where('tenant_id', $tenant->id)->count();
                                                    
                                                    $percent = $limit > 0 ? min(100, ($usage / $limit) * 100) : ($limit == -1 ? 0 : 100);
                                                    $color = $percent > 90 ? 'danger' : ($percent > 70 ? 'warning' : 'success');
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="bg-light rounded-4 p-3 border">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                    <i class="fas {{ $f['icon'] }} text-primary small"></i>
                                                                </div>
                                                                <span class="fw-bold small">{{ $f['label'] }}</span>
                                                            </div>
                                                            <span class="small text-muted fw-bold">
                                                                {{ $usage }} / {{ $limit == -1 ? '∞' : $limit }}
                                                            </span>
                                                        </div>
                                                        <div class="progress rounded-pill shadow-none border" style="height: 8px;">
                                                            <div class="progress-bar bg-{{ $color }} rounded-pill" role="progressbar" style="width: {{ $percent }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-light border rounded-4 mt-4 p-3">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; flex-shrink: 0;">
                                            <i class="fas fa-headset fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">هل تحتاج لترقية باقتك؟</h6>
                                            <p class="small text-muted mb-0">إذا كنت ترغب في رفع حدود الاستهلاك أو إضافة مميزات جديدة، يرجى التواصل مع الدعم الفني.</p>
                                        </div>
                                        <a href="https://wa.me/201271948834" target="_blank" class="btn btn-outline-primary rounded-pill ms-auto px-4 btn-sm">تواصل معنا</a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-credit-card fa-4x text-light"></i>
                                    </div>
                                    <h5 class="fw-bold">لا يوجد اشتراك نشط حالياً</h5>
                                    <p class="text-muted">يرجى التواصل مع الإدارة لتفعيل اشتراكك والبدء في استخدام كافة خدمات المنصة.</p>
                                    <a href="https://wa.me/201271948834" target="_blank" class="btn btn-primary rounded-pill px-5 mt-3 shadow-sm">طلب تفعيل اشتراك</a>
                                </div>
                            @endif
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
