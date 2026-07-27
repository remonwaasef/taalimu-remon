@extends('center::layouts.app-next')

@section('panel-content')
    @if(session('generated_password'))
        @php
            $msg = "مرحباً " . session('student_name') . "،\nيسعدنا انضمامك إلينا! 🎉\n\nبيانات الدخول الخاصة بك:\nرابط المنصة: " . url('/login') . "\nاسم المستخدم: " . (session('student_phone') ?? $student->phone) . "\nكلمة المرور: " . session('generated_password') . "\n\nنصيحة: سيُطلب منك تغيير كلمة المرور عند أول دخول للأمان.";
            $whatsappUrl = "https://wa.me/" . sanitizePhoneForWhatsApp(session('student_phone') ?? $student->phone) . "?text=" . urlencode($msg);
            $mailtoUrl = "mailto:" . (session('student_email') ?? $student->email) . "?subject=تم إعادة تعيين كلمة مرورك&body=" . rawurlencode($msg);

            $qrUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addMinutes(15), ['student' => $student->id, 'tenant' => app('tenant')->domain]);
        @endphp

        @include('center::students.partials._show-password-ticket')
    @endif

    <div class="row g-4 animate__animated animate__fadeIn">

        @include('center::students.partials._show-header')

        @include('center::students.partials._show-tabs-nav')

        <!-- Main Content Area (Full Width) -->
        <div class="col-12">
            <div class="tab-content">
                @include('center::students.partials._show-tab-info')
                @include('center::students.partials._show-tab-academic')
                @include('center::students.partials._show-tab-attendance')
                @include('center::students.partials._show-tab-courses')
                @include('center::students.partials._show-tab-financial')
                @include('center::students.partials._show-tab-activity')
                @include('center::students.partials._show-tab-points')
                @include('center::students.partials._show-tab-bookings')
            </div>
        </div>
    </div>

    @include('center::students.partials._show-modals')
    @include('center::students.partials._show-id-card')
    @include('center::students.partials._show-styles')
@endsection

@push('scripts')
    @include('center::students.partials._show-scripts')
@endpush
