@extends('instructor::components.layouts.master')

@section('page-title', 'إضافة طالب جديد')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">بيانات الطالب الجديد</h4>
                    <p class="text-muted small">قم بإدخال بيانات الطالب وسيقوم النظام بفتح حساب له تلقائياً.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('instructor.students.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-12">
                                <label class="form-label fw-bold">اسم الطالب</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-user" style="color: var(--primary-color);"></i></span>
                                    <input type="text" name="name" class="form-control bg-light border-0 focus-ring-primary" placeholder="مثال: أحمد محمد علي" required value="{{ old('name') }}">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">رقم هاتف الطالب</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-phone" style="color: var(--primary-color);"></i></span>
                                    <input type="tel" name="phone" class="form-control bg-light border-0 focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون رقم الهاتف مكون من 11 رقم" value="{{ old('phone') }}">
                                </div>
                                <small class="text-muted mt-1 d-block">سيستخدم هذا الرقم كاسم مستخدم وكلمة مرور أولية.</small>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">البريد الإلكتروني (اختياري)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-envelope" style="color: var(--primary-color);"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 focus-ring-primary" placeholder="example@mail.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Parent Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">رقم هاتف ولي الأمر</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-users" style="color: var(--primary-color);"></i></span>
                                    <input type="tel" name="parent_phone" class="form-control bg-light border-0 focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون رقم الهاتف مكون من 11 رقم" value="{{ old('parent_phone') }}">
                                </div>
                            </div>

                            <!-- Course Selection -->
                            <div class="col-12">
                                <label class="form-label fw-bold">المجموعة المستهدفة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-layer-group" style="color: var(--primary-color);"></i></span>
                                    <select name="course_id" class="form-select bg-light border-0 focus-ring-primary" required>
                                        <option value="" disabled selected>اختر المجموعة لتسجيل الطالب بها...</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold border-0" style="background: var(--primary-color); box-shadow: 0 4px 12px rgba(58, 12, 163, 0.2);">
                                    <i class="fas fa-user-plus me-2"></i> حفظ وتسجيل الطالب
                                </button>
                                <a href="{{ route('instructor.students.list') }}" class="btn btn-light w-100 rounded-pill py-3 mt-2 text-muted fw-bold border-0">
                                    إلغاء والعودة
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .focus-ring-primary:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(58, 12, 163, 0.1) !important;
        background-color: white !important;
    }
</style>
@endpush
