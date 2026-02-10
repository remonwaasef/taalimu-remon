@extends('layouts.landing-new')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --font-outfit: 'Outfit', sans-serif;
        --font-cairo: 'Cairo', sans-serif;
        --brand-primary: #2563eb;
        --brand-gradient: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #60a5fa 100%);
        --panel-dark: #0F172A;
    }
    
    body {
        font-family: var(--font-outfit);
        background-color: #f8faff;
    }
    
    [lang="ar"] body, .font-arabic {
        font-family: var(--font-cairo);
    }

    .type-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 2px solid transparent;
        border-radius: 24px;
        background: #FFFFFF;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .type-card.selected {
        border-color: var(--brand-primary);
        background: rgba(37, 99, 235, 0.05);
        box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.1), 0 10px 10px -6px rgba(37, 99, 235, 0.04);
        transform: translateY(-5px);
    }

    .type-card:hover:not(.selected) {
        border-color: #E2E8F0;
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .type-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 20px;
        transition: all 0.4s ease;
    }

    .type-card:hover .type-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .btn-submit-elite {
        border-radius: 50px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        background: var(--brand-gradient);
        background-size: 200% auto;
        color: white;
        border: none;
        font-weight: 800;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
    }

    .btn-submit-elite:hover:not(:disabled) {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5);
    }

    .btn-submit-elite:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="min-h-screen bg-slate-50/50 flex flex-col items-center justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay" 
     style="padding-top: 60px;"
     x-data="{ selectedType: '' }"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <div class="max-w-4xl w-full text-center mb-12 animate-fade-in-up">
        <h1 class="text-3xl lg:text-5xl font-black text-slate-900 mb-4 font-arabic tracking-tight">
            {{ __('auth.center_type.title') }}
        </h1>
        <p class="text-slate-500 text-lg font-arabic font-light max-w-2xl mx-auto">
            {{ __('auth.center_type.subtitle') }}
        </p>
    </div>

    <form action="{{ route('register.setup.store') }}" method="POST" class="w-full max-w-5xl">
        @csrf
        <input type="hidden" name="center_type" x-model="selectedType">
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 animate-fade-in-up" style="animation-delay: 0.1s">
            <!-- Tutoring Center -->
            <div @click="selectedType = 'tutoring'" :class="selectedType === 'tutoring' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-blue-100 text-blue-600">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.tutoring') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    دروس خصوصية، مراحل تعليمية، متابعة طلاب
                </p>
            </div>

            <!-- Language Center -->
            <div @click="selectedType = 'languages'" :class="selectedType === 'languages' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-emerald-100 text-emerald-600">
                    <i class="bi bi-translate"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.languages') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    كورسات لغات، مستويات، تدريب دولي
                </p>
            </div>

            <!-- Quran Center -->
            <div @click="selectedType = 'quran'" :class="selectedType === 'quran' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-amber-100 text-amber-600">
                    <i class="bi bi-book-half"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.quran') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    تحفيظ قرآن، حلقات ذكر، مستويات حفظ
                </p>
            </div>

            <!-- Vocational Training -->
            <div @click="selectedType = 'vocational'" :class="selectedType === 'vocational' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-purple-100 text-purple-600">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.vocational') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    تدريب مهني، ورش عمل، شهادات خبرة
                </p>
            </div>

            <!-- Institute -->
            <div @click="selectedType = 'institute'" :class="selectedType === 'institute' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-indigo-100 text-indigo-600">
                    <i class="bi bi-building-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.institute') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    معاهد تعليمية، دبلومات، مسار أكاديمي شامل
                </p>
            </div>

            <!-- Other -->
            <div @click="selectedType = 'other'" :class="selectedType === 'other' ? 'selected' : ''" class="type-card p-8">
                <div class="type-icon bg-slate-100 text-slate-600">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2 font-arabic">{{ __('auth.center_type.types.other') }}</h3>
                <p class="text-slate-500 text-sm font-arabic font-light leading-relaxed">
                    تخصيص حر لكافة الإعدادات وفقاً لاحتياجك
                </p>
            </div>
        </div>

        <div class="flex justify-center animate-fade-in-up" style="animation-delay: 0.2s">
            <button type="submit" :disabled="!selectedType" class="h-16 px-12 btn-submit-elite flex items-center gap-3 active:scale-95 group">
                <span class="text-xl font-black font-arabic">{{ __('auth.center_type.continue') }}</span>
                <i class="bi bi-arrow-right-short text-3xl group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</div>
@endsection
