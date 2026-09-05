@extends('center::layouts.app-next')

@section('panel-content')
    @if(session('generated_password'))
        @php
            $studentPhone = session('student_phone') ?? $student->phone;
            $cleanPhone = sanitizePhoneForWhatsApp($studentPhone);
            $qrUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addHours(24), ['student' => $student->id, 'tenant' => app('tenant')->domain]);
            $studentEmail = session('student_email') ?? $student->email;
            $hasCustomEmail = $studentEmail && !preg_match('/^std\d+\./', $studentEmail);
            $tenantName = app('tenant')->name ?? 'المركز';
            $loginUrl = url('/login');
            $studentName = session('student_name') ?? $student->name;

            // Clean, concise credentials message
            $whatsappText = "مرحباً {$studentName}، تم تحديث بيانات حسابك في {$tenantName}! 🎉\n\nبيانات تسجيل الدخول لحسابك:\n📱 رقم الهاتف: {$studentPhone}\n🔑 كلمة المرور: " . session('generated_password') . "\n🌐 رابط المنصة: {$loginUrl}";
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
