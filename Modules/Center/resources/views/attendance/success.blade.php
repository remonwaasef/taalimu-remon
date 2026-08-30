@extends('layouts.auth-minimal')

@section('title', __('center::attendance.attendance_recorded') ?? 'تم تسجيل الحضور')

@section('content')
<div class="w-full max-w-md my-8 animate-fadeIn text-center">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        
        <!-- Animated Success Icon -->
        <div class="w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 flex items-center justify-center text-3xl mx-auto mb-4 border-4 border-emerald-100 dark:border-emerald-900/60 shadow-xs animate-bounce">
            <i class="fas fa-check"></i>
        </div>

        <h2 class="text-xl font-black text-slate-900 dark:text-white mb-2">
            تم تسجيل الحضور بنجاح! 🎉
        </h2>

        <p class="text-xs text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
            {{ $message ?? 'تم تسجيل بيانات حضورك للحصة بنجاح.' }}
        </p>

        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400 space-y-1.5 mb-6">
            <div class="flex items-center justify-between">
                <span>تاريخ اليوم:</span>
                <span class="font-bold text-slate-700 dark:text-slate-200 font-mono" dir="ltr">{{ today()->format('Y-m-d') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span>وقت التسجيل:</span>
                <span class="font-bold text-brand-primary dark:text-brand-300 font-mono" dir="ltr">{{ now()->format('h:i A') }}</span>
            </div>
        </div>

        <button 
            type="button" 
            onclick="window.close();" 
            class="w-full py-3 px-4 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition-all"
        >
            إغلاق هذه الصفحة
        </button>
    </div>

    <p class="text-[11px] text-slate-400 mt-4 m-0">
        Taalimu — النظام التعليمي الذكي
    </p>
</div>
@endsection
