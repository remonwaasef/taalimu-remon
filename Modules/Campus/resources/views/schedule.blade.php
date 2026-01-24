@extends('campus::layouts.master')

@section('content')
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-2">الجدول الدراسي</h2>
            <p class="text-muted mb-0">مواعيد محاضراتك ودوراتك التدريبية خلال الأسبوع</p>
        </div>
    </div>

    <div class="row g-4 text-center py-5 mt-5">
        <div class="col-12">
            <div class="mb-4 display-1 opacity-25">🗓️</div>
            <h4 class="fw-bold text-dark">نظام الجدولة قيد التحديث</h4>
            <p class="text-muted mb-4 max-w-500 mx-auto">نقوم حالياً بتطوير نظام الجدولة الذكي ليوفر لك تجربة متابعة أفضل. تواصل مع إدارة المركز لمعرفة مواعيدك الحالية.</p>
            <div class="p-4 bg-white rounded-ultra shadow-sm d-inline-block border border-light">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                        <i class="bi bi-info-circle fs-4"></i>
                    </div>
                    <div class="text-start">
                        <div class="fw-bold">هل لديك استفسار؟</div>
                        <div class="text-muted small">يمكنك دائماً مراجعة قائمة دوراتك لمعرفة تفاصيل كل دورة.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
