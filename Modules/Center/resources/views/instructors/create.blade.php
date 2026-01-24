@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">إضافة مدرس جديد</h2>
        <a href="{{ route('center.instructors.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('center.instructors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">اسم المدرس</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg bg-light border-0">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">التخصص</label>
                            <input type="text" name="specialization" value="{{ old('specialization') }}" class="form-control form-control-lg bg-light border-0" placeholder="مثال: رياضيات، فيزياء...">
                            @error('specialization')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg bg-light border-0">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">رقم الهاتف</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control form-control-lg bg-light border-0">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">نبذة تعريفية</label>
                            <textarea name="bio" class="form-control form-control-lg bg-light border-0" rows="4">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">الصورة الشخصية</label>
                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0" accept="image/*">
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">حفظ البيانات</button>
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
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

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

        // Name: Letters only
        const nameInput = document.querySelector('input[name="name"]');
        if(nameInput) {
            nameInput.addEventListener('input', function() {
                let original = this.value;
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'حروف فقط (أ-ي, A-Z)');
                }
            });
        }

        // Specialization: Letters, dots, dashes
        const specInput = document.querySelector('input[name="specialization"]');
        if(specInput) {
            specInput.addEventListener('input', function() {
                let original = this.value;
                // Allow letters, spaces, dots, dashes.
                // Regex matches what we want to REMOVE.
                // Remove digits and most symbols, but keep . and -
                let clean = original.replace(/[0-9!@#$%^&*()_+\=\[\]{};':"\\|,<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, 'نص فقط (بدون أرقام أو رموز خاصة)');
                }
            });
        }
    });
</script>
@endsection
