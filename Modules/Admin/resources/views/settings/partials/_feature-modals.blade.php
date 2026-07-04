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
