@extends('instructor::components.layouts.master')

@section('page-title', 'إنشاء مجموعة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0">بيانات المجموعة الجديدة</h4>
                    <p class="text-muted small">أدخل تفاصيل المجموعة لإتاحة التسجيل للطلاب</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.groups.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">اسم المجموعة (مثلاً: فيزياء الصف الثالث الثانوي - مجموعة أ)</label>
                                <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="أدخل اسم المجموعة">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">وصف المجموعة (اختياري)</label>
                                <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="3" placeholder="اكتب وصفاً موجزاً للمجموعة...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">سعر المجموعة (ج.م)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control rounded-start-3 py-2 @error('price') is-invalid @enderror" value="{{ old('price') }}" required step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text rounded-end-3 bg-light border-start-0">ج.م</span>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">عدد الحصص/المحاضرات</label>
                                <input type="number" name="sessions_count" class="form-control rounded-3 py-2 @error('sessions_count') is-invalid @enderror" value="{{ old('sessions_count') }}" required min="1" placeholder="مثلاً: 8">
                                @error('sessions_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm border-0">
                                        <i class="fas fa-save me-2"></i> إنشاء المجموعة
                                    </button>
                                    <a href="{{ route('instructor.groups.list') }}" class="btn btn-light rounded-pill px-4 py-2 text-muted fw-bold">إلغاء</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
