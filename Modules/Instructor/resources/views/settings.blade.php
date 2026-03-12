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

                            {{-- 1. Consumption Overview (Status) --}}
                            <div class="bg-light rounded-4 p-4 border mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-chart-pie me-2"></i> نظرة عامة على استهلاك الموارد</h6>
                                    @if($subscription)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success py-2 px-3 rounded-pill shadow-sm"><i class="fas fa-check-circle me-1"></i> اشتراك نشط</span>
                                            @if($subscription->ends_at)
                                                <small class="text-muted fw-bold x-small">ينتهي في: {{ $subscription->ends_at->format('Y/m/d') }}</small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="row g-4">
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
                                            
                                            $isUnlimited = $limit === 'unlimited' || $limit == -1;
                                            $percent = 0;
                                            if (!$isUnlimited && is_numeric($limit) && $limit > 0) {
                                                $percent = min(100, ($usage / (float)$limit) * 100);
                                            } elseif (!$isUnlimited) {
                                                $percent = 100;
                                            }
                                            $color = $percent > 90 ? 'danger' : ($percent > 70 ? 'warning' : 'success');
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-4 p-3 border shadow-sm h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                            <i class="fas {{ $f['icon'] }} x-small"></i>
                                                        </div>
                                                        <span class="small fw-bold">{{ $f['label'] }}</span>
                                                    </div>
                                                    <span class="x-small text-muted fw-bold">{{ $usage }} / {{ $isUnlimited ? '∞' : $limit }}</span>
                                                </div>
                                                <div class="progress rounded-pill shadow-none mb-1" style="height: 6px; background: #f1f5f9;">
                                                    <div class="progress-bar bg-{{ $color }} rounded-pill" role="progressbar" style="width: {{ $percent }}%"></div>
                                                </div>
                                                <div class="text-start">
                                                    <span class="x-small text-{{ $color }} fw-bold">{{ round($percent) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 2. Plans Comparison Grid --}}
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-layer-group small"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">الخطط والترقيات المتاحة</h5>
                                </div>
                                
                                <div class="row g-4">
                                    @foreach($packages as $pkg)
                                        @php
                                            $isCurrent = $package && $package->id == $pkg->id;
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="card border rounded-4 shadow-sm h-100 {{ $isCurrent ? 'border-primary border-2' : ($pkg->is_featured ? 'border-primary' : '') }} position-relative overflow-hidden transition-all hover-shadow">
                                                
                                                @if($isCurrent)
                                                    <div class="bg-primary text-white text-center py-2 fw-bold" style="font-size: 11px;">
                                                        <i class="fas fa-star me-1"></i> باقتك الحالية
                                                    </div>
                                                @elseif($pkg->is_featured)
                                                    <div class="bg-secondary text-white text-center py-1 position-absolute w-100" style="top: 0; left: 0; font-size: 10px; font-weight: bold; z-index: 10;">
                                                        الموصى به
                                                    </div>
                                                @endif

                                                <div class="card-body p-4 {{ $isCurrent ? 'pt-4' : 'pt-5' }}">
                                                    <h5 class="fw-bold mb-2">{{ $pkg->name }}</h5>
                                                    <div class="mb-4">
                                                        <span class="fs-2 fw-bold text-primary">{{ number_format($pkg->price) }}</span>
                                                        <small class="text-muted">جنيه / شهرياً</small>
                                                    </div>
                                                    
                                                    <hr class="opacity-25 mb-4">

                                                    <ul class="list-unstyled mb-4">
                                                        @foreach($pkg->features as $feature)
                                                            @php
                                                                $val = $feature->pivot->value;
                                                                $displayVal = $val;
                                                                if($val == '-1' || $val == 'unlimited') $displayVal = 'غير محدود';
                                                                
                                                                $icon = 'fa-check-circle text-success';
                                                                if($feature->type == 'boolean') {
                                                                    $displayVal = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? 'متاح' : 'غير متاح';
                                                                    $icon = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
                                                                }
                                                            @endphp
                                                            <li class="small mb-2 d-flex align-items-center gap-2">
                                                                <i class="fas {{ $icon }}" style="font-size: 12px;"></i>
                                                                <span class="text-muted">{{ $feature->name }}:</span>
                                                                <span class="fw-bold">{{ $displayVal }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    @if($isCurrent)
                                                        <div class="alert alert-primary bg-opacity-10 border-0 mb-0 py-3 text-center rounded-4">
                                                            <span class="fw-bold small text-primary"><i class="fas fa-check-circle me-1"></i> باقة مفعلة</span>
                                                            @if($subscription->ends_at)
                                                                <div class="x-small text-muted mt-1">تنتهي: {{ $subscription->ends_at->format('Y/m/d') }}</div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <a href="{{ route('center.subscription.checkout', ['package' => $pkg->id, 'tenant' => $tenant->domain ?? $tenant->id]) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold py-2">اشتراك الآن</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
