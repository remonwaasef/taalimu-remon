@extends('center::layouts.master')

@section('title', 'الإعدادات العامة')

@section('page-title', 'الإعدادات العامة')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 p-0">
                    <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                                <i class="fas fa-info-circle me-2"></i> عام
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-graduation-cap me-2"></i> أكاديمي
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-coins me-2"></i> مالي
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-paint-brush me-2"></i> المظهر
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab" aria-selected="false">
                                <i class="fab fa-whatsapp me-2 text-success"></i> واتساب
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-user-shield me-2 text-danger"></i> الخصوصية والبيانات
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                <!-- Logo -->
                                <div class="col-md-6 text-center border-end">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-xl rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold display-4 shadow-sm overflow-hidden" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            @if($tenant->logo)
                                                <img src="{{ asset('storage/' . $tenant->logo) }}" class="w-100 h-100 object-fit-contain p-2">
                                            @else
                                                {{ substr($tenant->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <label for="logo" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted small"></i>
                                        </label>
                                        <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold">شعار المركز</p>
                                </div>
                                <!-- Favicon -->
                                <div class="col-md-6 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-lg rounded bg-light d-flex align-items-center justify-content-center text-primary shadow-sm overflow-hidden" style="width: 60px; height: 60px; margin-top: 20px;">
                                            @if($tenant->favicon)
                                                <img src="{{ asset('storage/' . $tenant->favicon) }}" class="w-100 h-100 object-fit-contain p-2">
                                            @else
                                                <i class="fas fa-globe fs-2"></i>
                                            @endif
                                        </div>
                                        <label for="favicon" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted x-small"></i>
                                        </label>
                                        <input type="file" id="favicon" name="favicon" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold">الأيقونة (Favicon)</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">اسم المركز</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">رقم الهاتف</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">البريد الإلكتروني</label>
                                    <input type="email" class="form-control bg-light" value="{{ $tenant->email ?? ($tenant->users->first()?->email ?? 'N/A') }}" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">العنوان</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $tenant->address) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">وصف المركز</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $tenant->description) }}</textarea>
                                </div>

                                <!-- Social Media Links -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-share-alt me-2"></i>روابط التواصل الاجتماعي</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $tenant->facebook_url) }}" placeholder="رابط فيسبوك">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $tenant->instagram_url) }}" placeholder="رابط إنستغرام">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="url" name="twitter_url" class="form-control" value="{{ old('twitter_url', $tenant->twitter_url) }}" placeholder="رابط تويتر">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-youtube text-danger"></i></span>
                                                <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $tenant->youtube_url) }}" placeholder="رابط يوتيوب">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Academic Settings -->
                        <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                            <form action="{{ route('center.settings.update-academic', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" id="academicStructureForm">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">السنة الدراسية ونظام الدرجات</h6>
                                <div class="row g-3 pb-4 border-bottom mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">السنة الدراسية الحالية</label>
                                    <select name="settings[academic][year]" class="form-select">
                                        <option value="2024-2025" {{ ($tenant->settings['academic']['year'] ?? '') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                        <option value="2025-2026" {{ ($tenant->settings['academic']['year'] ?? '') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                        <option value="2026-2027" {{ ($tenant->settings['academic']['year'] ?? '') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">نظام الدرجات</label>
                                    <select name="settings[academic][grading]" class="form-select">
                                        <option value="100" {{ ($tenant->settings['academic']['grading'] ?? '') == '100' ? 'selected' : '' }}>مئوي (0-100)</option>
                                        <option value="GPA" {{ ($tenant->settings['academic']['grading'] ?? '') == 'GPA' ? 'selected' : '' }}>المعدل التراكمي (GPA)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[academic][attendance_alert]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[academic][attendance_alert]" value="1" id="attendanceAlert" {{ ($tenant->settings['academic']['attendance_alert'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label user-select-none" for="attendanceAlert">تفعيل تنبيهات الغياب تلقائياً لولي الأمر</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Stages & Grades Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-layer-group me-2"></i>هيكل المراحل والصفوف الدراسية</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addStage()">
                                    <i class="fas fa-plus me-1"></i> إضافة مرحلة
                                </button>
                            </div>

                            <div id="stages-container">
                                    @foreach($stages as $sIndex => $stage)
                                        <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="{{ $sIndex }}">
                                            <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                                                <input type="hidden" name="stages[{{ $sIndex }}][id]" value="{{ $stage->id }}">
                                                <input type="text" name="stages[{{ $sIndex }}][name]" class="form-control form-control-sm fw-bold border-0 bg-light" value="{{ $stage->name }}" placeholder="اسم المرحلة (مثلاً: الابتدائية)">
                                                <div class="ms-auto d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade({{ $sIndex }})" title="إضافة صف">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this, {{ $stage->id }})" title="حذف مرحلة">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="grades-container d-flex flex-wrap gap-2">
                                                    @foreach($stage->grades as $gIndex => $grade)
                                                        <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                                                            <input type="hidden" name="stages[{{ $sIndex }}][grades][{{ $gIndex }}][id]" value="{{ $grade->id }}">
                                                            <input type="text" name="stages[{{ $sIndex }}][grades][{{ $gIndex }}][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" value="{{ $grade->name }}" placeholder="اسم الصف">
                                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this, {{ $grade->id }})">
                                                                <i class="fas fa-times-circle"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="deletion-inputs"></div>

                                <div class="mt-4 text-center">
                                    <button type="submit" form="academicStructureForm" class="btn btn-primary px-5 rounded-pill shadow-sm">
                                        <i class="fas fa-save me-2"></i> حفظ الهيكل الأكاديمي
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Financial Settings -->
                        <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">إعدادات الفواتير والعملة</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">العملة الافتراضية</label>
                                    <select name="settings[financial][currency]" class="form-select">
                                        <option value="EGP" {{ ($tenant->settings['financial']['currency'] ?? '') == 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                                        <option value="SAR" {{ ($tenant->settings['financial']['currency'] ?? '') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                                        <option value="USD" {{ ($tenant->settings['financial']['currency'] ?? '') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                                        <option value="EUR" {{ ($tenant->settings['financial']['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">نسبة الضريبة (%)</label>
                                    <input type="number" name="settings[financial][tax_rate]" class="form-control" value="{{ $tenant->settings['financial']['tax_rate'] ?? '0' }}" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">بادئة الفاتورة</label>
                                    <input type="text" name="settings[financial][invoice_prefix]" class="form-control" value="{{ $tenant->settings['financial']['invoice_prefix'] ?? 'INV-' }}" placeholder="INV-">
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Appearance Settings -->
                        <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">تخصيص المظهر</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">لون النظام الأساسي</label>
                                    <input type="color" name="settings[appearance][primary_color]" class="form-control form-control-color w-100" value="{{ $tenant->settings['appearance']['primary_color'] ?? '#140342' }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[appearance][dark_mode]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[appearance][dark_mode]" value="1" id="darkMode" {{ ($tenant->settings['appearance']['dark_mode'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label user-select-none" for="darkMode">تفعيل الوضع الليلي (Dark Mode)</label>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- WhatsApp Settings -->
                        <div class="tab-pane fade" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                        <i class="fab fa-whatsapp fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">الربط مع واتساب (UltraMsg)</h5>
                                        <p class="text-muted small mb-0">قم بتفعيل الإشعارات التلقائية لأولياء الأمور والطلاب.</p>
                                    </div>
                                </div>

                                <div class="card border bg-light shadow-none mb-4">
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-4">
                                            <input type="hidden" name="settings[whatsapp][enabled]" value="0">
                                            <input class="form-check-input" type="checkbox" name="settings[whatsapp][enabled]" value="1" id="whatsappEnabled" {{ ($tenant->settings['whatsapp']['enabled'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="whatsappEnabled">تفعيل خدمة واتساب</label>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Instance ID</label>
                                                <input type="text" name="settings[whatsapp][instance_id]" class="form-control" value="{{ $tenant->settings['whatsapp']['instance_id'] ?? '' }}" placeholder="مثل: instance12345">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Token (API Key)</label>
                                                <input type="text" name="settings[whatsapp][token]" class="form-control" value="{{ $tenant->settings['whatsapp']['token'] ?? '' }}" placeholder="رمز الوصول الخاص بك">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 rounded-4">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i>ما هي الرسائل التي سيتم إرسالها؟</h6>
                                    <ul class="small mb-0 mt-2">
                                        <li><strong>إشعار الحضور:</strong> بمجرد تحضير الطالب في الحصة، سيصل لولي الأمر "تحرك من المركز: الطالب [الاسم] حضر الآن...".</li>
                                        <li><strong>إشعار الدفع:</strong> عند استلام أي دفعة مالية، سيصل "تم استلام دفعة بقيمة [المبلغ]... المتبقي [الباقي]".</li>
                                    </ul>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> حفظ الإعدادات
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Privacy & GDPR Settings -->
                        <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                            <div class="alert alert-warning border-0 rounded-4 mb-4">
                                <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i>منطقة التحكم في البيانات (GDPR)</h6>
                                <p class="small mb-0 mt-1">
                                    تتيح لك هذه الإعدادات ممارسة حقوقك في الوصول إلى بياناتك الشخصية (Right to Access) أو حذفها نهائيًا (Right to be Forgotten).
                                </p>
                            </div>

                            <!-- Data Export -->
                            <div class="card border bg-light shadow-none mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1">تصدير بياناتي الشخصية</h6>
                                            <p class="text-muted small mb-0">تحميل نسخة كاملة من بياناتك (الملف الشخصي، الاختبارات، السجلات) بصيغة JSON.</p>
                                        </div>
                                        <a href="{{ route('gdpr.export') }}" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> تحميل البيانات
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Account -->
                            <div class="card border border-danger bg-danger bg-opacity-10 shadow-none">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-danger mb-2">حذف الحساب نهائيًا (Danger Zone)</h6>
                                    <p class="text-secondary small mb-3">
                                        سيؤدي هذا الإجراء إلى حذف جميع بياناتك الشخصية وإخفاء هويتك من السجلات العامة للمركز. هذا الإجراء <strong>لا يمكن التراجع عنه</strong>.
                                    </p>
                                    
                                    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i> حذف حسابي
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal moved to root for stability -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">تأكيد حذف الحساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('gdpr.delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3 text-muted">للأمان، يرجى إدخال كلمة المرور الخاصة بك لتأكيد عملية الحذف.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">كلمة المرور الحالية</label>
                        <input type="password" name="password" class="form-control bg-light border-0" required placeholder="********">
                    </div>

                    <div class="form-check custom-check p-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="confirm_delete" id="confirmDelete" required>
                        <label class="form-check-label small user-select-none text-danger fw-bold" for="confirmDelete">
                            أنا أفهم أن هذا الإجراء نهائي ولا يمكن استرجاع البيانات.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">حذف نهائي</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let stageCount = {{ count($stages) }};

    function addStage() {
        const container = document.getElementById('stages-container');
        const stageHtml = `
            <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="${stageCount}">
                <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                    <input type="text" name="stages[${stageCount}][name]" class="form-control form-control-sm fw-bold border-0 bg-light" placeholder="اسم المرحلة (مثلاً: الابتدائية)">
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade(${stageCount})" title="إضافة صف">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this)" title="حذف مرحلة">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="grades-container d-flex flex-wrap gap-2">
                        <!-- Grades will be added here -->
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', stageHtml);
        stageCount++;
    }

    function addGrade(stageIndex) {
        const stageCard = document.querySelector(`.stage-card[data-index="${stageIndex}"]`);
        const container = stageCard.querySelector('.grades-container');
        const gradeIndex = container.children.length;
        const gradeHtml = `
            <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                <input type="text" name="stages[${stageIndex}][grades][${gradeIndex}][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" placeholder="اسم الصف">
                <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this)">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', gradeHtml);
    }

    function removeStage(btn, id = null) {
        if (confirm('هل أنت متأكد من حذف هذه المرحلة وجميع الصفوف التابعة لها؟')) {
            if (id) {
                const deletionInputs = document.getElementById('deletion-inputs');
                deletionInputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_stages[]" value="${id}">`);
            }
            btn.closest('.stage-card').remove();
        }
    }

    function removeGrade(btn, id = null) {
        if (id) {
            const deletionInputs = document.getElementById('deletion-inputs');
            deletionInputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_grades[]" value="${id}">`);
        }
        btn.closest('.grade-item').remove();
    }
</script>
@endpush
