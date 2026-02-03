@extends('center::layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">{{ isset($classroom) ? 'تعديل بيانات القاعة' : 'إضافة قاعة دراسية جديدة' }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('center.classrooms.index') }}" class="text-muted text-decoration-none">القاعات الدراسية</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">{{ isset($classroom) ? 'تعديل' : 'إضافة جديد' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- Input Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <form action="{{ isset($classroom) ? route('center.classrooms.update', $classroom) : route('center.classrooms.store') }}" method="POST" id="classroomForm">
                        @csrf
                        @if(isset($classroom)) @method('PUT') @endif

                        <h5 class="fw-bold text-dark mb-4"><i class="fas fa-info-circle me-2 text-primary"></i>البيانات الأساسية</h5>

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control rounded-3" id="nameInput" placeholder="اسم القاعة" value="{{ old('name', $classroom->name ?? '') }}">
                            <label for="nameInput">اسم القاعة (مثلاً: قاعة المتنبي)</label>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" name="capacity" class="form-control rounded-3" id="capacityInput" placeholder="السعة" value="{{ old('capacity', $classroom->capacity ?? '') }}">
                                    <label for="capacityInput">السعة الاستيعابية (طلاب)</label>
                                </div>
                                @error('capacity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="typeSelect" name="type">
                                        <option value="hall" {{ (old('type', $classroom->type ?? '') == 'hall') ? 'selected' : '' }}>قاعة محاضرات</option>
                                        <option value="lab" {{ (old('type', $classroom->type ?? '') == 'lab') ? 'selected' : '' }}>معمل حاسب</option>
                                        <option value="virtual" {{ (old('type', $classroom->type ?? '') == 'virtual') ? 'selected' : '' }}>قاعة افتراضية (Zoom/Meet)</option>
                                    </select>
                                    <label for="typeSelect">نوع القاعة</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">لون التمييز (للجدول)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $classroom->color ?? '#435ebe') }}" title="اختر لوناً">
                                <small class="text-muted">يظهر هذا اللون في الجدول الدراسي.</small>
                            </div>
                        </div>

                        <!-- Facilities (Visual Simulation for Future Dev) -->
                        <h5 class="fw-bold text-dark mt-4 mb-3"><i class="fas fa-layer-group me-2 text-primary"></i>التجهيزات (مقترح للتطوير)</h5>
                        <div class="d-flex flex-wrap gap-2 mb-4 opacity-50" title="خطوة تطويرية قادمة">
                            <span class="badge bg-light text-dark border p-2 rounded-pill"><i class="fas fa-wifi me-1"></i> انترنت عالِ السرعة</span>
                            <span class="badge bg-light text-dark border p-2 rounded-pill"><i class="fas fa-video me-1"></i> جهاز عرض (Projector)</span>
                            <span class="badge bg-light text-dark border p-2 rounded-pill"><i class="fas fa-snowflake me-1"></i> تكييف</span>
                            <span class="badge bg-light text-dark border p-2 rounded-pill"><i class="fas fa-chalkboard me-1"></i> سبورة ذكية</span>
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> {{ isset($classroom) ? 'حفظ التغييرات' : 'إضافة القاعة' }}
                            </button>
                            <a href="{{ route('center.classrooms.index') }}" class="btn btn-light px-4 rounded-pill border">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Preview Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white position-relative overflow-hidden">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4 opacity-75">معاينة القاعة</h5>
                    
                    <div class="d-flex justify-content-center mb-4">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-lg animate__animated animate__pulse animate__infinite" style="width: 120px; height: 120px;">
                            <i class="fas fa-chalkboard-teacher fa-4x text-white" id="previewIcon"></i>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-2" id="previewName">{{ $classroom->name ?? 'اسم القاعة...' }}</h3>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-users me-1"></i> سعة: <span id="previewCapacity">{{ $classroom->capacity ?? '--' }}</span> طالب
                    </p>
                </div>
                <!-- Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-shapes fa-5x"></i>
                </div>
            </div>
        
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm rounded-4 mt-3 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>نصائح التطوير</h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>يمكن ربط القاعة بالجدول الدراسي لمنع التعارض.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>تحديد السعة يساعد في ضبط الحضور.</li>
                        <li><i class="fas fa-hammer text-secondary me-2"></i>قريباً: إضافة صور للقاعة وجرد للأجهزة.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('nameInput');
            const capacityInput = document.getElementById('capacityInput');
            const previewName = document.getElementById('previewName');
            const previewCapacity = document.getElementById('previewCapacity');

            nameInput.addEventListener('input', function() {
                previewName.textContent = this.value || 'اسم القاعة...';
            });

            capacityInput.addEventListener('input', function() {
                previewCapacity.textContent = this.value || '--';
            });
        });
    </script>
@endsection
