@extends('instructor::components.layouts.master')

@section('page-title', 'تعديل بيانات المجموعة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0">تعديل المجموعة: {{ $course->title }}</h4>
                    <p class="text-muted small">تحديث بيانات المجموعة والمواعيد</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.groups.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">اسم المجموعة</label>
                                <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required placeholder="أدخل اسم المجموعة">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">وصف المجموعة (اختياري)</label>
                                <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="3" placeholder="اكتب وصفاً موجزاً للمجموعة...">{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">سعر المجموعة (ج.م)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control rounded-start-3 py-2 @error('price') is-invalid @enderror" value="{{ old('price', $course->price) }}" required step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text rounded-end-3 bg-light border-start-0">ج.م</span>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">عدد الحصص/المحاضرات</label>
                                <input type="number" name="sessions_count" class="form-control rounded-3 py-2 @error('sessions_count') is-invalid @enderror" value="{{ old('sessions_count', $course->sessions_count) }}" required min="1" placeholder="مثلاً: 8">
                                @error('sessions_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                    <a href="{{ route('instructor.groups.list') }}" class="btn btn-light rounded-pill px-4 py-2 text-muted">إلغاء</a>
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
