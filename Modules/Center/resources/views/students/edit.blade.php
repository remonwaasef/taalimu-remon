@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل بيانات الطالب</h2>
        <a href="{{ route('center.students.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- 1. Student Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-person me-2"></i>البيانات الشخصية</h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">اسم الطالب</label>
                                <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control form-control-lg bg-light border-0">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">البريد الإلكتروني (للدخول)</label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control form-control-lg bg-light border-0">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">كود الطالب</label>
                                <input type="text" name="code" value="{{ old('code', $student->code) }}" class="form-control form-control-lg bg-light border-0">
                                @error('code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">الرقم القومي (اختياري)</label>
                                <input type="text" name="national_id" value="{{ old('national_id', $student->national_id) }}" class="form-control form-control-lg bg-light border-0">
                                @error('national_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">رقم الهاتف</label>
                                <input type="tel" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="أرقام فقط (+ - مسافات)">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">تاريخ الميلاد</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}" class="form-control form-control-lg bg-light border-0">
                                @error('birth_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">الجنس</label>
                                <select name="gender" class="form-select form-select-lg bg-light border-0">
                                    <option value="">اختر...</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">العنوان</label>
                                <input type="text" name="address" value="{{ old('address', $student->address) }}" class="form-control form-control-lg bg-light border-0" placeholder="المنطقة، الشارع...">
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">الصورة الشخصية</label>
                                @if($student->profile_photo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($student->profile_photo) }}" alt="Profile Photo" class="rounded-circle" width="60" height="60">
                                    </div>
                                @endif
                                <input type="file" name="profile_photo" class="form-control bg-light border-0" accept="image/*">
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- 2. Parent Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-people me-2"></i>بيانات ولي الأمر</h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">اسم ولي الأمر</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" class="form-control form-control-lg bg-light border-0">
                                @error('parent_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">صلة القرابة</label>
                                <input type="text" name="parent_relation" value="{{ old('parent_relation', $student->parent_relation) }}" class="form-control form-control-lg bg-light border-0" placeholder="الأب، الأم، الأخ...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">رقم ولي الأمر</label>
                                <div class="input-group">
                                    <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="أرقام فقط (+ - مسافات)">
                                    <span class="input-group-text bg-light border-0 {{ $student->guardian_id ? '' : 'd-none' }}" id="guardian-found-badge">
                                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill"></i> ولي أمر مسجل</span>
                                    </span>
                                </div>
                                <div id="guardian-info-alert" class="alert alert-success border-0 rounded-4 small mt-2 d-none">
                                    <i class="bi bi-info-circle-fill me-1"></i>
                                    تم العثور على <b><span id="found-guardian-name"></span></b> في النظام. سيتم ربط الطالب بهذا الحساب والاستفادة من نظام الإخوة.
                                </div>
                                @error('parent_phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">رقم الطوارئ (بديل)</label>
                                <input type="tel" name="emergency_phone" value="{{ old('emergency_phone', $student->emergency_phone) }}" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="أرقام فقط (+ - مسافات)">
                                @error('emergency_phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">وظيفة ولي الأمر</label>
                                <input type="text" name="parent_job" value="{{ old('parent_job', $student->parent_job) }}" class="form-control form-control-lg bg-light border-0">
                                @error('parent_job')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- 3. Academic Info --}}
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-mortarboard me-2"></i>البيانات الدراسية</h5>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">الصف الدراسي</label>
                                <select name="grade_id" class="form-select form-select-lg bg-light border-0">
                                    <option value="">اختر الصف...</option>
                                    @foreach($stages as $stage)
                                        <optgroup label="📂 {{ $stage->name }}">
                                            @foreach($stage->grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id', $student->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">المدرسة</label>
                                <input type="text" name="school_name" value="{{ old('school_name', $student->school_name) }}" class="form-control form-control-lg bg-light border-0">
                                @error('school_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">الشعبة / التخصص</label>
                                <input type="text" name="section_type" value="{{ old('section_type', $student->section_type) }}" class="form-control form-control-lg bg-light border-0" placeholder="عام، علمي، أدبي...">
                                @error('section_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function showWarning(input, msg) {
            // Remove existing warning if any
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            // Create new warning
            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            // Fade out and remove
            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

        // 1. Phone numbers: allow only digits and controls
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'أرقام فقط (0-9)');
                }
            });
        });

        // 2. Names: allow only letters and spaces (Arabic & English)
        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"], input[name="parent_job"], input[name="parent_relation"], input[name="section_type"]');
        nameInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                // Remove digits and special symbols
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'حروف فقط (أ-ي, A-Z)');
                }
            });
        });

        // 3. Guardian Lookup by Phone
        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        
        const parentNameInput = document.querySelector('input[name="parent_name"]');
        const parentJobInput = document.querySelector('input[name="parent_job"]');
        const addressInput = document.querySelector('input[name="address"]');

        let timeout = null;
        parentPhoneInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const phone = this.value.trim();
            
            if (phone.length >= 8) {
                timeout = setTimeout(() => {
                    fetch(`{{ route('center.guardians.lookup') }}?phone=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.found) {
                                badge.classList.remove('d-none');
                                alertBox.classList.remove('d-none');
                                nameSpan.textContent = data.guardian.name;
                                
                                // Auto-fill if empty
                                if (!parentNameInput.value) parentNameInput.value = data.guardian.name;
                                if (!parentJobInput.value) parentJobInput.value = data.guardian.job || '';
                                if (!addressInput.value) addressInput.value = data.guardian.address || '';
                            } else {
                                badge.classList.add('d-none');
                                alertBox.classList.add('d-none');
                            }
                        })
                        .catch(err => console.error('Error looking up guardian:', err));
                }, 500);
            } else {
                badge.classList.add('d-none');
                alertBox.classList.add('d-none');
            }
        });
    });
</script>
@endsection
