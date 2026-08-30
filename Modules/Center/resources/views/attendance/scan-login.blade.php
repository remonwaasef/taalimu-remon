@extends('layouts.auth-minimal')

@section('title', __('center::attendance.scan_attendance_code'))

@section('content')
<div class="w-full max-w-md my-8 animate-fadeIn">
    <!-- Center / Course Branding -->
    <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-2xl bg-brand-50 dark:bg-brand-900/40 text-brand-primary dark:text-brand-300 flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
            <i class="fas fa-qrcode"></i>
        </div>
        <h1 class="text-xl font-black text-slate-900 dark:text-white m-0">
            {{ __('center::attendance.scan_attendance_code') }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ $schedule->course->title }} • {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
        </p>
    </div>

    <!-- Login Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        @if(isset($message))
            <div class="mb-5 p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-xs font-semibold text-amber-800 dark:text-amber-300 text-center flex items-center gap-2 justify-center">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <span>{{ $message }}</span>
            </div>
        @else
            <div class="mb-5 p-3.5 rounded-2xl bg-brand-50 dark:bg-brand-900/30 border border-brand-200 dark:border-brand-800/60 text-xs font-semibold text-brand-primary dark:text-brand-300 text-center flex items-center gap-2 justify-center">
                <i class="fas fa-user-check text-sm"></i>
                <span>يرجى تسجيل الدخول بحساب الطالب لتأكيد الحضور</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-3.5 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-xs font-bold text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <p class="m-0 flex items-center gap-1.5 justify-center"><i class="fas fa-times-circle"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('center.attendance.loginAndMark', array_merge(['schedule' => $schedule], request()->query())) }}" class="space-y-4">
            @csrf
            <input type="hidden" name="qr_url" value="{{ $qrUrl ?? request()->fullUrl() }}">
            
            <!-- Email / Phone Field -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    البريد الإلكتروني
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                        <i class="fas fa-envelope text-xs"></i>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="student@example.com"
                        required 
                        autofocus
                        class="w-full ps-10 pe-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all"
                    >
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    كلمة المرور
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                        <i class="fas fa-lock text-xs"></i>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required 
                        class="w-full ps-10 pe-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all"
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white bg-brand-primary hover:bg-brand-900 transition-all shadow-xs flex items-center justify-center gap-2 mt-2"
            >
                <i class="fas fa-check-circle text-xs"></i>
                <span>تسجيل الدخول وتأكيد الحضور</span>
            </button>
        </form>
    </div>

    <!-- Security Footnote -->
    <p class="text-center text-[11px] text-slate-400 mt-4 m-0">
        <i class="fas fa-shield-alt me-1 text-slate-400"></i> نظام الحضور الذكي الآمن — Taalimu
    </p>
</div>
@endsection
