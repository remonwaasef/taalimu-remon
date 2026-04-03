@extends('layouts.auth-minimal')

@section('title', __('إتمام الدفع'))

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            إتمام عملية الدفع
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            يرجى إدخال بيانات البطاقة بأمان لإتمام اشتراكك
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 relative">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <div class="flex items-center text-emerald-600 font-semibold">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    دفع آمن ومحمي
                </div>
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 text-sm font-medium transition-colors">
                    إلغاء والعودة
                </a>
            </div>
            
            <div class="w-full" style="height: 650px;">
                <iframe src="{{ $redirectUrl }}" 
                        class="w-full h-full border-0 rounded-lg"
                        allow="payment"
                        title="Secure Payment Form">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection
