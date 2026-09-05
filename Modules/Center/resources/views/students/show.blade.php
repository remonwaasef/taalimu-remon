@extends('center::layouts.app-next')

@section('panel-content')
    @if(session('generated_password'))
        @php
            $cleanPhone = sanitizePhoneForWhatsApp(session('student_phone') ?? $student->phone);
            $qrUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addHours(24), ['student' => $student->id, 'tenant' => app('tenant')->domain]);

            // Secure Message for WhatsApp (Signed Magic Link - No passwords exposed in URL GET params)
            $secureWhatsAppMsg = "مرحباً " . (session('student_name') ?? $student->name) . "،\nيسعدنا انضمامك إلى " . (app('tenant')->name ?? 'المركز') . "! 🎉\n\nبيانات الدخول لحسابك:\nالبريد: " . (session('student_email') ?? $student->email) . "\n\nرابط الدخول الآمن المباشر:\n" . $qrUrl . "\n\n(هذا الرابط مشفر ومخصص لك لتعيين كلمة مرورك والدخول مباشرة)";

            // Full Message with Temporary Password (for Clipboard Copying, never leaked in browser address bar)
            $fullCredentialsMsg = "مرحباً " . (session('student_name') ?? $student->name) . "،\nيسعدنا انضمامك إلى " . (app('tenant')->name ?? 'المركز') . "! 🎉\n\nبيانات الدخول الخاصة بك:\nرابط المنصة: " . url('/login') . "\nالبريد: " . (session('student_email') ?? $student->email) . "\nكلمة المرور المؤقتة: " . session('generated_password') . "\n\n(يرجى تغيير كلمة المرور عند أول تسجيل دخول للأمان)";

            $waWebUrl = "https://web.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . urlencode($secureWhatsAppMsg);
            $waAppUrl = "whatsapp://send?phone=" . $cleanPhone . "&text=" . urlencode($secureWhatsAppMsg);
            $mailtoUrl = "mailto:" . (session('student_email') ?? $student->email) . "?subject=تم إعادة تعيين كلمة مرورك&body=" . rawurlencode($fullCredentialsMsg);
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
