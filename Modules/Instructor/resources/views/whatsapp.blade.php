@extends('instructor::components.layouts.master')

@section('page-title', 'إعدادات الواتساب (UltraMsg)')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">ربط خدمة WhatsApp</h4>
                            <p class="text-muted small mb-0">قم بربط حساب UltraMsg الخاص بك لإرسال الرسائل التلقائية.</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 border-top">
                    <form action="{{ route('instructor.whatsapp.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-check form-switch mb-4 p-0 d-flex align-items-center gap-3">
                            <label class="form-check-label fw-bold cursor-pointer" for="whatsappEnabled">تفعيل الخدمة</label>
                            <input class="form-check-input ms-0" type="checkbox" name="enabled" id="whatsappEnabled" value="1" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">كود الدولة الافتراضي</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-globe text-muted"></i></span>
                                    <input type="text" name="country_code" class="form-control bg-light border-0 focus-ring-primary" value="{{ $settings['country_code'] ?? '20' }}" placeholder="مثال: 20" required>
                                </div>
                                <div class="form-text mt-1 small">سيتم إضافة هذا الكود تلقائياً لأرقام الهواتف (مثلاً 20 لمصر).</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">ID النسخة (Instance ID)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                    <input type="text" name="instance_id" class="form-control bg-light border-0 focus-ring-primary" value="{{ $settings['instance_id'] ?? '' }}" placeholder="مثال: instance12345" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">الرمز السري (Token)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="token" class="form-control bg-light border-0 focus-ring-primary" value="{{ $settings['token'] ?? '' }}" placeholder="أدخل الـ Token الخاص بك" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> قوالب الرسائل (Message Templates)</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="alert alert-info border-0 shadow-none rounded-3 py-2 px-3 mb-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="small">
                                            المتغيرات المتاحة: 
                                            <code class="mx-1">:student_name</code> (اسم الطالب) ، 
                                            <code class="mx-1">:course_name</code> (اسم المجموعة) ، 
                                            <code class="mx-1">:tenant_name</code> (اسم المركز) ،
                                            <code class="mx-1">:amount</code> (المبلغ) ،
                                            <code class="mx-1">:remaining</code> (المتبقي).
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">رسالة تسجيل الحضور</label>
                                <textarea name="attendance_template" class="form-control bg-light border-0 rounded-3 text-start" rows="4" placeholder="اترك فارغاً لاستخدام النص الافتراضي">{{ $settings['attendance_template'] ?? '' }}</textarea>
                                <div class="form-text x-small mt-1 text-muted">تُرسل عند تحضير الطالب.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">رسالة تأكيد الدفع</label>
                                <textarea name="payment_template" class="form-control bg-light border-0 rounded-3 text-start" rows="4" placeholder="اترك فارغاً لاستخدام النص الافتراضي">{{ $settings['payment_template'] ?? '' }}</textarea>
                                <div class="form-text x-small mt-1 text-muted">تُرسل عند تحصيل اشتراك.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">رسالة التذكير بالمديونية</label>
                                <textarea name="debt_template" class="form-control bg-light border-0 rounded-3 text-start" rows="4" placeholder="اترك فارغاً لاستخدام النص الافتراضي">{{ $settings['debt_template'] ?? '' }}</textarea>
                                <div class="form-text x-small mt-1 text-muted">تُرسل للتذكير بالمبالغ المتأخرة.</div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 rounded-4 bg-opacity-10 py-3">
                            <h6 class="fw-bold fs-6"><i class="fas fa-lightbulb me-2 text-primary"></i> خطوات الربط سريعة جداً:</h6>
                            <ul class="small mb-0 mt-2 text-secondary">
                                <li>قم بإنشاء حساب في <a href="https://ultramsg.com" target="_blank" class="fw-bold">UltraMsg.com</a></li>
                                <li>قم بمسح كود الـ QR من داخل لوحة التحكم لربط هاتفك.</li>
                                <li>انسخ الـ **Instance ID** والـ **Token** وضعهما في المربعات أعلاه.</li>
                                <li>سيقوم النظام بإرسال تنبيهات الحضور والغياب والمديونات تلقائياً.</li>
                            </ul>
                        </div>

                        <div class="mt-4 pt-4 border-top text-center">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="fas fa-save me-2"></i> حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .focus-ring-primary:focus {
        background-color: white !important;
        border: 1px solid var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(58, 12, 163, 0.1);
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
</style>
@endpush
@endsection
